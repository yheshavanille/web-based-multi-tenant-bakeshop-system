<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceReview;
use App\Models\ProductReview;
use App\Models\BranchProduct;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Orders extends Component
{
    public $orders = [];
    public $statusCounts = [];
    public $selectedStatus = 'all';

    public $showReviewModal = false;
    public $selectedOrder = null;
    public $serviceRating = 0;
    public $employeeRating = 0;
    public $serviceReviewText = '';
    public $productRatings = [];
    public $productReviews = [];

    public function mount()
    {
        $this->loadOrders();
        $this->loadStatusCounts();
    }

    public function loadOrders()
    {
        $query = Order::where('customer_id', Auth::id())
            ->with(['items.product', 'shop', 'branch', 'serviceReview']);

        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
        }

        $this->orders = $query->orderBy('created_at', 'desc')->get();
    }

    public function loadStatusCounts()
    {
        $this->statusCounts = [
            'pending' => Order::where('customer_id', Auth::id())->where('status', 'pending')->count(),
            'preparing' => Order::where('customer_id', Auth::id())->where('status', 'preparing')->count(),
            'ready_for_pickup' => Order::where('customer_id', Auth::id())->where('status', 'ready_for_pickup')->count(),
            'completed' => Order::where('customer_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled' => Order::where('customer_id', Auth::id())->where('status', 'cancelled')->count(),
        ];
    }

    public function updatedSelectedStatus()
    {
        $this->loadOrders();
    }

    public function cancelOrder($orderId)
    {
        Log::info('Cancel Order clicked', ['order_id' => $orderId, 'user_id' => Auth::id()]);

        $order = Order::where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            Log::error('Order not found or not pending', ['order_id' => $orderId]);
            session()->flash('error', 'This order cannot be cancelled.');
            return;
        }

        Log::info('Order found, cancelling...', ['order_id' => $order->id]);

        // ✅ ONLY RESTORE STOCK FOR ITEMS THAT WERE COMPLETED (stock was reduced)
        foreach ($order->items as $item) {
            // Only restore stock if the item was already completed (stock was reduced)
            if ($item->status === 'completed') {
                $branchProduct = BranchProduct::where('branch_id', $item->branch_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($branchProduct) {
                    $oldStock = $branchProduct->stock;
                    $branchProduct->increment('stock', $item->quantity);

                    StockHistory::create([
                        'product_id' => $item->product_id,
                        'branch_id' => $item->branch_id,
                        'user_id' => Auth::id(),
                        'old_stock' => $oldStock,
                        'new_stock' => $branchProduct->stock,
                        'notes' => 'Order #' . $order->order_number . ' - Order cancelled by customer (stock restored)',
                    ]);
                }
            }
        }

        // ✅ UPDATE ORDER STATUS TO CANCELLED
        $order->update(['status' => 'cancelled']);

        // ✅ UPDATE ALL ITEMS TO CANCELLED
        $order->items()->update(['status' => 'cancelled']);

        $this->loadOrders();
        $this->loadStatusCounts();
        session()->flash('message', 'Order cancelled successfully.');
    }

    public function cancelItem($itemId)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        })->findOrFail($itemId);

        if ($item->status !== 'pending') {
            session()->flash('error', 'This item cannot be cancelled.');
            return;
        }

        // ✅ ONLY RESTORE STOCK IF ITEM WAS COMPLETED (stock was reduced)
        // Since we're only allowing cancellation of pending items, stock was NEVER reduced
        // So we should NOT restore stock for pending items
        // The stock was never reduced, so we skip restoration

        $item->update(['status' => 'cancelled']);

        $order = $item->order;
        $this->recalculateOrderStatus($order);

        $this->loadOrders();
        $this->loadStatusCounts();
        session()->flash('message', 'Item cancelled successfully.');
    }

    private function recalculateOrderStatus($order)
    {
        $itemStatuses = $order->items()->pluck('status')->toArray();

        $pendingCount = count(array_filter($itemStatuses, function ($s) {
            return $s === 'pending';
        }));
        $completedCount = count(array_filter($itemStatuses, function ($s) {
            return $s === 'completed';
        }));
        $cancelledCount = count(array_filter($itemStatuses, function ($s) {
            return $s === 'cancelled';
        }));
        $totalItems = count($itemStatuses);

        if ($cancelledCount === $totalItems) {
            $order->update(['status' => 'cancelled']);
        } elseif ($completedCount === $totalItems - $cancelledCount) {
            $order->update(['status' => 'completed']);
        } elseif ($pendingCount > 0) {
            $order->update(['status' => 'pending']);
        } else {
            $order->update(['status' => 'preparing']);
        }
    }

    public function openReviewModal($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'shop', 'serviceReview'])
            ->where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$this->selectedOrder) {
            session()->flash('error', 'Order not found.');
            return;
        }

        if ($this->selectedOrder->serviceReview) {
            session()->flash('error', 'You already reviewed this order.');
            return;
        }

        $this->showReviewModal = true;
        $this->serviceRating = 0;
        $this->employeeRating = 0;
        $this->serviceReviewText = '';
        $this->productRatings = [];
        $this->productReviews = [];
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->selectedOrder = null;
    }

    public function setRating($type, $rating)
    {
        if ($type === 'service') {
            $this->serviceRating = $rating;
        } elseif ($type === 'employee') {
            $this->employeeRating = $rating;
        }
    }

    public function setProductRating($productId, $rating)
    {
        $this->productRatings[$productId] = $rating;
    }

    public function submitReview()
    {
        if (!$this->selectedOrder) {
            session()->flash('error', 'No order selected.');
            return;
        }

        if ($this->serviceRating < 1) {
            session()->flash('error', 'Please rate the service quality.');
            return;
        }

        if ($this->employeeRating < 1) {
            session()->flash('error', 'Please rate the employee service.');
            return;
        }

        ServiceReview::create([
            'customer_id' => Auth::id(),
            'shop_id' => $this->selectedOrder->shop_id,
            'branch_id' => $this->selectedOrder->branch_id,
            'order_id' => $this->selectedOrder->id,
            'rating' => $this->serviceRating,
            'employee_rating' => $this->employeeRating,
            'review' => $this->serviceReviewText,
        ]);

        foreach ($this->productRatings as $productId => $rating) {
            if ($rating > 0) {
                ProductReview::create([
                    'customer_id' => Auth::id(),
                    'shop_id' => $this->selectedOrder->shop_id,
                    'order_id' => $this->selectedOrder->id,
                    'product_id' => $productId,
                    'rating' => $rating,
                    'review' => $this->productReviews[$productId] ?? null,
                ]);
            }
        }

        $this->selectedOrder->update(['service_review' => true]);

        $this->closeReviewModal();
        $this->loadOrders();
        session()->flash('message', 'Thank you for your review! ⭐');
    }

    public function render()
    {
        return view('livewire.customer.orders')
            ->layout('components.layouts.customer');
    }
}
