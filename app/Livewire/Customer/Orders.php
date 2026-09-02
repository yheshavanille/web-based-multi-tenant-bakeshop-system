<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceReview;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Orders extends Component
{
    public $orders = [];
    public $selectedStatus = 'all';
    public $search = '';
    public $showReviewModal = false;
    public $selectedOrder = null;
    public $serviceRating = 0;
    public $employeeRating = 0;
    public $serviceReviewText = '';
    public $productRatings = [];
    public $productReviews = [];

    public $showDetailsModal = false;
    public $selectedOrderDetails = null;

    public $showReviewDetailsModal = false;
    public $selectedReviewOrder = null;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = Order::where('customer_id', Auth::id())
            ->with(['items.product', 'shop', 'branch', 'serviceReview'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', $searchTerm)
                    ->orWhereHas('shop', function ($q2) use ($searchTerm) {
                        $q2->where('shop_name', 'like', $searchTerm);
                    })
                    ->orWhereHas('branch', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', $searchTerm);
                    });
            });
        }

        $this->orders = $query->get();
    }

    public function updatedSelectedStatus()
    {
        $this->loadOrders();
    }

    public function updatedSearch()
    {
        $this->loadOrders();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadOrders();
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        if ($order->status !== 'pending') {
            session()->flash('error', 'Only pending orders can be cancelled.');
            return;
        }

        foreach ($order->items as $item) {
            $item->update(['status' => 'cancelled']);
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_by' => 'customer',
        ]);

        session()->flash('message', 'Order cancelled successfully.');
        $this->loadOrders();
    }

    public function cancelItem($itemId)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        })->findOrFail($itemId);

        if ($item->status !== 'pending') {
            session()->flash('error', 'Only pending items can be cancelled.');
            return;
        }

        $item->update(['status' => 'cancelled']);

        $order = $item->order;
        $this->recalculateOrderStatus($order);

        if ($order->status === 'cancelled') {
            $order->update(['cancelled_by' => 'customer']);
        }

        session()->flash('message', 'Item cancelled successfully.');
        $this->loadOrders();
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

    public function openDetailsModal($orderId)
    {
        // ✅ Use select() to only get needed columns and load relationships efficiently
        $this->selectedOrderDetails = Order::where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->with(['items' => function ($query) {
                $query->with('product')->where('status', '!=', 'cancelled');
            }, 'shop', 'branch'])
            ->first();

        if (!$this->selectedOrderDetails) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrderDetails = null;
    }

    public function openReviewDetailsModal($orderId)
    {
        $this->selectedReviewOrder = Order::with([
            'items.product',
            'serviceReview',
            'shop'
        ])->where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$this->selectedReviewOrder || !$this->selectedReviewOrder->serviceReview) {
            session()->flash('error', 'No review found for this order.');
            return;
        }

        $this->showReviewDetailsModal = true;
    }

    public function closeReviewDetailsModal()
    {
        $this->showReviewDetailsModal = false;
        $this->selectedReviewOrder = null;
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
        if ($this->serviceRating < 1) {
            session()->flash('error', 'Please rate the service quality.');
            return;
        }

        if ($this->employeeRating < 1) {
            session()->flash('error', 'Please rate the employee service.');
            return;
        }

        try {
            $serviceReview = ServiceReview::create([
                'customer_id' => Auth::id(),
                'shop_id' => $this->selectedOrder->shop_id,
                'branch_id' => $this->selectedOrder->branch_id,
                'order_id' => $this->selectedOrder->id,
                'rating' => $this->serviceRating,
                'employee_rating' => $this->employeeRating,
                'review' => $this->serviceReviewText,
            ]);

            Log::info('Service review saved', ['service_review' => $serviceReview]);

            foreach ($this->productRatings as $productId => $rating) {
                if ($rating > 0) {
                    $productReview = ProductReview::create([
                        'customer_id' => Auth::id(),
                        'shop_id' => $this->selectedOrder->shop_id,
                        'order_id' => $this->selectedOrder->id,
                        'product_id' => $productId,
                        'rating' => $rating,
                        'review' => $this->productReviews[$productId] ?? null,
                    ]);
                    Log::info('Product review saved', [
                        'product_id' => $productId,
                        'product_review' => $productReview,
                    ]);
                }
            }

            $this->selectedOrder->update([
                'service_review' => true,
            ]);

            session()->flash('message', 'Thank you for your review! ⭐');
            $this->closeReviewModal();
            $this->loadOrders();
        } catch (\Exception $e) {
            Log::error('Review submission failed', [
                'order_id' => $this->selectedOrder->id,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to submit review. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.customer.orders')
            ->layout('components.layouts.customer');
    }
}
