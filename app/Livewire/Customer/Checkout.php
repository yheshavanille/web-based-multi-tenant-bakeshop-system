<?php

namespace App\Livewire\Customer;

use App\Models\Branch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use App\Models\Employee;
use App\Services\PayMongoService;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Checkout extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $tax = 0;
    public $grandTotal = 0;
    public $total = 0;
    public $shopId;
    public $shop;
    public $branches = [];
    public $branchSelections = [];
    public $pickupTimes = [];
    public $payment_method = 'pickup_payment';
    public $payment_method_detail = 'gcash';
    public $notes = '';
    public $isProcessing = false;

    public function setPaymentDetail($value)
    {
        $this->payment_method_detail = $value;
    }

    public function mount()
    {
        $this->loadCart();

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->route('livewire.customer.browse-shops');
        }

        $firstItem = $this->cartItems->first();
        $this->shopId = $firstItem->product->shop_id;
        $this->shop = Shop::find($this->shopId);

        foreach ($this->cartItems as $item) {
            $availableBranches = Branch::where('shop_id', $this->shopId)
                ->where('is_active', true)
                ->whereHas('products', function ($query) use ($item) {
                    $query->where('product_id', $item->product_id)
                        ->where('branch_product.stock', '>', 0);
                })
                ->get();

            if ($availableBranches->isNotEmpty()) {
                $this->branchSelections[$item->id] = $availableBranches->first()->id;
            }

            $this->pickupTimes[$item->id] = now()->addMinutes(30)->format('Y-m-d\TH:i');
        }
    }

    public function loadCart()
    {
        $selectedCartIds = session()->get('checkout_items', []);

        if (empty($selectedCartIds)) {
            $this->cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $this->cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->whereIn('id', $selectedCartIds)
                ->get();
        }

        session()->forget('checkout_items');
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            $product = $item->product;
            $price = $product->isDiscounted() ? $product->getDiscountedPrice() : $product->price;
            return $price * $item->quantity;
        });

        $this->tax = round($this->subtotal * 0.12, 2);
        $this->grandTotal = $this->subtotal + $this->tax;
        $this->total = $this->grandTotal;
    }

    public function getAvailableBranches($cartItemId)
    {
        $item = $this->cartItems->firstWhere('id', $cartItemId);
        if (!$item) {
            return collect();
        }

        return Branch::where('shop_id', $this->shopId)
            ->where('is_active', true)
            ->whereHas('products', function ($query) use ($item) {
                $query->where('product_id', $item->product_id)
                    ->where('branch_product.stock', '>', 0);
            })
            ->get();
    }

    public function getBranchName($branchId)
    {
        if (!$branchId) return 'Not selected';
        $branch = Branch::find($branchId);
        return $branch ? $branch->name : 'Not selected';
    }

    public function updatedBranchSelections()
    {
        // The view will automatically update via Livewire
    }

    public function placeOrder()
    {
        $this->validate([
            'payment_method' => 'required|in:paymongo,pickup_payment',
        ]);

        Log::info('Payment method detail:', [
            'payment_method_detail' => $this->payment_method_detail,
            'payment_method' => $this->payment_method,
        ]);

        if ($this->payment_method === 'paymongo' && empty($this->payment_method_detail)) {
            $this->payment_method_detail = 'gcash';
        }

        $itemsByBranch = [];
        foreach ($this->cartItems as $item) {
            $branchId = $this->branchSelections[$item->id] ?? null;
            if (!$branchId) {
                session()->flash('error', 'Please select a branch for: ' . $item->product->name);
                return;
            }
            $branch = Branch::find($branchId);
            if ($branch) {
                $pivot = $branch->products()->where('product_id', $item->product_id)->first();
                if ($pivot && $pivot->pivot->stock < $item->quantity) {
                    session()->flash('error', 'Not enough stock for ' . $item->product->name . '. Only ' . $pivot->pivot->stock . ' left.');
                    return;
                }
            }
            $itemsByBranch[$branchId][] = $item;
        }

        foreach ($this->cartItems as $item) {
            if (!isset($this->pickupTimes[$item->id]) || empty($this->pickupTimes[$item->id])) {
                session()->flash('error', 'Please select a pickup time for: ' . $item->product->name);
                return;
            }
        }

        $this->isProcessing = true;
        $createdOrders = [];

        foreach ($itemsByBranch as $branchId => $items) {
            $orderNumber = 'ORD-' . strtoupper(uniqid());

            $subtotal = 0;
            foreach ($items as $item) {
                $product = $item->product;
                $price = $product->isDiscounted() ? $product->getDiscountedPrice() : $product->price;
                $subtotal += $price * $item->quantity;
            }
            $tax = round($subtotal * 0.12, 2);
            $grandTotal = $subtotal + $tax;

            $firstItem = $items[0];
            $pickupTime = $this->pickupTimes[$firstItem->id] ?? now()->addMinutes(30);

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => Auth::id(),
                'shop_id' => $this->shopId,
                'branch_id' => $branchId,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'total_amount' => $grandTotal,
                'status' => 'pending',
                'payment_method' => $this->payment_method,
                'payment_method_detail' => $this->payment_method === 'paymongo' ? $this->payment_method_detail : null,
                'payment_status' => 'pending',
                'pickup_time' => $pickupTime,
                'notes' => $this->notes,
            ]);

            foreach ($items as $item) {
                $product = $item->product;
                $price = $product->isDiscounted() ? $product->getDiscountedPrice() : $product->price;
                $originalPrice = $product->price;
                $pickupTime = $this->pickupTimes[$item->id] ?? now()->addMinutes(30);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'branch_id' => $branchId,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'pickup_time' => $pickupTime,
                    'status' => 'pending',
                ]);

                $branch = Branch::find($branchId);
                if ($branch) {
                    $pivot = $branch->products()->where('product_id', $item->product_id)->first();
                    if ($pivot) {
                        $currentStock = $pivot->pivot->stock;
                        $pivot->pivot->update(['stock' => $currentStock - $item->quantity]);
                    }
                }
            }

            $createdOrders[] = $order;
        }

        $cartIds = $this->cartItems->pluck('id')->toArray();
        Cart::where('user_id', Auth::id())->whereIn('id', $cartIds)->delete();

        $this->dispatch('cartUpdated');

        foreach ($createdOrders as $order) {
            $this->notifyOrderManagers($order);
        }

        $this->isProcessing = false;
        $this->dispatch('refreshNotifications');

        // ✅ If payment is PayMongo, combine all orders into ONE payment
        if ($this->payment_method === 'paymongo' && count($createdOrders) > 0) {
            $totalAmount = 0;
            foreach ($createdOrders as $order) {
                $totalAmount += $order->total_amount;
            }

            $paymentOrder = $createdOrders[0];
            $paymentOrder->update([
                'total_amount' => $totalAmount,
                'subtotal' => round($totalAmount / 1.12, 2),
                'tax_amount' => round($totalAmount - ($totalAmount / 1.12), 2),
                'notes' => $this->notes,
            ]);

            return $this->processEPayment($paymentOrder);
        }

        // ✅ For Cash on Pickup - pass ALL order IDs to confirmation page
        if ($this->payment_method === 'pickup_payment' && count($createdOrders) > 0) {
            $orderIds = collect($createdOrders)->pluck('id')->implode(',');
            session()->flash('order_success', count($createdOrders) . ' orders placed successfully!');
            return redirect()->route('livewire.customer.order-confirmation', ['order' => $orderIds]);
        }

        if (count($createdOrders) === 1) {
            session()->flash('order_success', 'Order placed successfully!');
            return redirect()->route('livewire.customer.order-confirmation', ['order' => $createdOrders[0]->id]);
        } else {
            session()->flash('message', count($createdOrders) . ' orders placed successfully!');
            return redirect()->route('livewire.customer.orders');
        }
    }

    private function processEPayment($order)
    {
        try {
            $payMongoService = new PayMongoService();
            if (!$payMongoService->isConfigured()) {
                throw new \Exception('PayMongo is not configured. Please add your API keys to .env');
            }

            $paymentMethodDetail = $order->payment_method_detail ?? 'gcash';

            Log::info('Creating PayMongo payment for order', [
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method_detail' => $paymentMethodDetail,
            ]);

            $result = $payMongoService->createPaymentIntent($order);

            if (!isset($result['data']['attributes']['next_action']['redirect']['url'])) {
                Log::error('PayMongo response missing redirect URL', ['response' => $result]);
                throw new \Exception('Payment redirect URL not found.');
            }

            $checkoutUrl = $result['data']['attributes']['next_action']['redirect']['url'];

            $order->update([
                'payment_method_detail' => $paymentMethodDetail,
            ]);

            Log::info('Redirecting to PayMongo', ['url' => $checkoutUrl]);

            $this->isProcessing = false;

            return redirect()->away($checkoutUrl);
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Log::error('Payment processing error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Payment processing failed: ' . $e->getMessage());
            return redirect()->route('livewire.customer.cart');
        }
    }

    private function notifyOrderManagers($order)
    {
        Log::info('🔔 Looking for order managers for shop: ' . $this->shopId);

        $orderManagers = Employee::where('shop_id', $this->shopId)
            ->where('role', 'order_manager')
            ->where('is_active', true)
            ->with('user')
            ->get();

        Log::info('🔔 Found ' . $orderManagers->count() . ' order managers');

        foreach ($orderManagers as $manager) {
            Log::info('🔔 Order manager: ' . ($manager->user->name ?? 'No user') . ' (active: ' . ($manager->is_active ? 'yes' : 'no') . ')');
        }

        $users = $orderManagers->pluck('user')->filter();

        if ($users->count() > 0) {
            Notification::send($users, new OrderPlacedNotification($order));
            Log::info('✅ Notification sent to ' . $users->count() . ' order managers');
        } else {
            Log::warning('⚠️ No active order managers found for shop ' . $this->shopId);
        }

        $owner = $order->shop->user;
        if ($owner) {
            Notification::send($owner, new OrderPlacedNotification($order));
            Log::info('✅ Notification sent to owner: ' . $owner->name);
        }
    }

    public function render()
    {
        return view('livewire.customer.checkout')
            ->layout('components.layouts.customer');
    }
}
