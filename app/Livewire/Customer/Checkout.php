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
    public $total = 0;
    public $shopId;
    public $shop;
    public $branches = [];
    public $branchSelections = [];
    public $pickupTimes = [];
    public $payment_method = 'pickup_payment';
    public $notes = '';
    public $isProcessing = false;

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
        $this->total = $this->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
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

        // Check if all items have branch selections
        foreach ($this->cartItems as $item) {
            if (!isset($this->branchSelections[$item->id]) || empty($this->branchSelections[$item->id])) {
                session()->flash('error', 'Please select a branch for: ' . $item->product->name);
                return;
            }
            if (!isset($this->pickupTimes[$item->id]) || empty($this->pickupTimes[$item->id])) {
                session()->flash('error', 'Please select a pickup time for: ' . $item->product->name);
                return;
            }
        }

        // Check stock before placing order
        foreach ($this->cartItems as $item) {
            $branch = Branch::find($this->branchSelections[$item->id]);
            if ($branch) {
                $pivot = $branch->products()->where('product_id', $item->product_id)->first();
                if ($pivot && $pivot->pivot->stock < $item->quantity) {
                    session()->flash('error', 'Not enough stock for ' . $item->product->name . '. Only ' . $pivot->pivot->stock . ' left.');
                    return;
                }
            }
        }

        $this->isProcessing = true;

        // Generate order number
        $orderNumber = 'ORD-' . strtoupper(uniqid());

        // Create order
        $primaryBranch = $this->branchSelections[$this->cartItems->first()->id] ?? null;

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => Auth::id(),
            'shop_id' => $this->shopId,
            'branch_id' => $primaryBranch,
            'total_amount' => $this->total,
            'status' => 'pending',
            'payment_method' => $this->payment_method,
            'payment_status' => 'pending',
            'pickup_time' => now(),
            'notes' => $this->notes,
        ]);

        // Create order items with individual branch and pickup time
        foreach ($this->cartItems as $item) {
            $selectedBranchId = $this->branchSelections[$item->id];
            $pickupTime = $this->pickupTimes[$item->id];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'branch_id' => $selectedBranchId,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'pickup_time' => $pickupTime,
                'status' => 'pending',
            ]);
        }

        // Clear cart - ONLY delete items that were ordered
        $cartIds = $this->cartItems->pluck('id')->toArray();
        Cart::where('user_id', Auth::id())->whereIn('id', $cartIds)->delete();

        $this->dispatch('cartUpdated');

        // Handle payment based on method
        if ($this->payment_method === 'pickup_payment') {
            // ✅ Cash on Pickup - Send notification and redirect
            $this->notifyOrderManagers($order);
            $this->isProcessing = false;
            session()->flash('order_success', 'Order placed successfully!');
            return redirect()->route('livewire.customer.order-confirmation', ['order' => $order->id]);
        } else {
            // ✅ E-Payment - Process with PayMongo
            return $this->processEPayment($order);
        }
    }

    private function processEPayment($order)
    {
        try {
            // ✅ Check if PayMongo is configured
            $payMongoService = new PayMongoService();
            if (!$payMongoService->isConfigured()) {
                throw new \Exception('PayMongo is not configured. Please add your API keys to .env');
            }

            Log::info('Creating PayMongo source for order', [
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'amount' => $order->total_amount,
            ]);

            $result = $payMongoService->createPaymentIntent($order);

            // ✅ Validate response
            if (!isset($result['data']['attributes']['next_action']['redirect']['url'])) {
                Log::error('PayMongo response missing redirect URL', ['response' => $result]);
                throw new \Exception('Payment redirect URL not found.');
            }

            $redirectUrl = $result['data']['attributes']['next_action']['redirect']['url'];

            Log::info('Redirecting to PayMongo', ['url' => $redirectUrl]);

            $this->isProcessing = false;

            return redirect()->away($redirectUrl);
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
        $orderManagers = Employee::where('shop_id', $this->shopId)
            ->where('role', 'order_manager')
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();

        if ($orderManagers->count() > 0) {
            Notification::send($orderManagers, new OrderPlacedNotification($order));
        }

        $owner = $order->shop->user;
        if ($owner) {
            Notification::send($owner, new OrderPlacedNotification($order));
        }
    }

    public function render()
    {
        return view('livewire.customer.checkout')
            ->layout('components.layouts.customer');
    }
}
