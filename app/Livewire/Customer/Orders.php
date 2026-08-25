<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\ServiceReview;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Orders extends Component
{
    public $orders = [];
    public $showReviewModal = false;
    public $selectedOrder = null;
    public $serviceRating = 0;
    public $employeeRating = 0;
    public $serviceReviewText = '';
    public $productRatings = [];
    public $productReviews = [];

    // ✅ Order Details Modal
    public $showDetailsModal = false;
    public $selectedOrderDetails = null;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::where('customer_id', Auth::id())
            ->with(['items.product', 'shop', 'branch', 'serviceReview'])
            ->orderBy('created_at', 'desc')
            ->get();
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

    // ✅ Open Order Details Modal
    public function openDetailsModal($orderId)
    {
        $this->selectedOrderDetails = Order::with(['items.product', 'shop', 'branch'])
            ->where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$this->selectedOrderDetails) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $this->showDetailsModal = true;
    }

    // ✅ Close Order Details Modal
    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrderDetails = null;
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
        // ✅ Validate
        if ($this->serviceRating < 1) {
            session()->flash('error', 'Please rate the service quality.');
            return;
        }

        if ($this->employeeRating < 1) {
            session()->flash('error', 'Please rate the employee service.');
            return;
        }

        try {
            // ✅ Save Service Review
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

            // ✅ Save Product Reviews
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

            // ✅ Mark order as reviewed
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
