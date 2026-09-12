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
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->with(['items.product', 'shop', 'branch', 'serviceReview'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedStatus !== 'all') {
            if ($this->selectedStatus === 'no_show') {
                $query->whereHas('items', function ($q) {
                    $q->where('status', 'no_show');
                });
            } elseif ($this->selectedStatus === 'completed') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled']);
                })->where('status', 'completed');
            } elseif ($this->selectedStatus === 'ready_for_pickup') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'pending', 'preparing']);
                })->where('status', 'ready_for_pickup');
            } elseif ($this->selectedStatus === 'preparing') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'pending', 'ready_for_pickup', 'completed']);
                })->where('status', 'preparing');
            } elseif ($this->selectedStatus === 'pending') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'preparing', 'ready_for_pickup', 'completed']);
                })->where('status', 'pending');
            } else {
                $query->where('status', $this->selectedStatus);
            }
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
            return in_array($s, ['cancelled', 'no_show']);
        }));
        $totalItems = count($itemStatuses);

        if ($cancelledCount === $totalItems) {
            $order->update(['status' => 'cancelled', 'payment_status' => 'refunded']);
            return;
        }

        if ($completedCount === $totalItems) {
            $order->update(['status' => 'completed', 'payment_status' => 'paid']);
            return;
        }

        if ($completedCount > 0 && ($pendingCount > 0 || $cancelledCount > 0)) {
            $order->update(['status' => 'partially_completed', 'payment_status' => 'partially_paid']);
            return;
        }

        if ($pendingCount > 0) {
            $order->update(['status' => 'pending', 'payment_status' => 'pending']);
            return;
        }

        $order->update(['status' => 'preparing', 'payment_status' => 'pending']);
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

        $hasCompletedItem = $this->selectedOrder->items->contains(function ($item) {
            return $item->status === 'completed';
        });

        if (!$hasCompletedItem) {
            session()->flash('error', 'You can only review orders with completed items.');
            return;
        }

        $this->showReviewModal = true;
        $this->serviceRating = 0;
        $this->employeeRating = 0;
        $this->serviceReviewText = '';
        $this->productRatings = [];
        $this->productReviews = [];
    }

    public function openEditReviewModal($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'serviceReview'])
            ->where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$this->selectedOrder) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $serviceReview = $this->selectedOrder->serviceReview;
        if ($serviceReview) {
            $this->serviceRating = $serviceReview->rating;
            $this->employeeRating = $serviceReview->employee_rating;
            $this->serviceReviewText = $serviceReview->review;
        }

        $existingProductReviews = ProductReview::where('order_id', $orderId)->get();
        foreach ($existingProductReviews as $review) {
            $this->productRatings[$review->product_id] = $review->rating;
            $this->productReviews[$review->product_id] = $review->review;
        }

        $this->showReviewModal = true;
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->selectedOrder = null;
    }

    public function openDetailsModal($orderId)
    {
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

        $this->dispatch('open-details-modal');
    }

    public function closeDetailsModal()
    {
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
            $serviceReview = ServiceReview::updateOrCreate(
                [
                    'order_id' => $this->selectedOrder->id,
                    'customer_id' => Auth::id()
                ],
                [
                    'shop_id' => $this->selectedOrder->shop_id,
                    'branch_id' => $this->selectedOrder->branch_id,
                    'rating' => $this->serviceRating,
                    'employee_rating' => $this->employeeRating,
                    'review' => $this->serviceReviewText,
                ]
            );

            Log::info('Service review saved', ['service_review' => $serviceReview]);

            foreach ($this->productRatings as $productId => $rating) {
                if ($rating > 0) {
                    $productReview = ProductReview::updateOrCreate(
                        [
                            'order_id' => $this->selectedOrder->id,
                            'customer_id' => Auth::id(),
                            'product_id' => $productId,
                        ],
                        [
                            'shop_id' => $this->selectedOrder->shop_id,
                            'rating' => $rating,
                            'review' => $this->productReviews[$productId] ?? null,
                        ]
                    );
                    Log::info('Product review saved', [
                        'product_id' => $productId,
                        'product_review' => $productReview,
                    ]);
                }
            }

            $this->selectedOrder->update([
                'service_review' => true,
            ]);

            session()->flash('message', 'Review updated successfully! ⭐');
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

    // ✅ Calculate the 4-section breakdown with product lists
    public function getBreakdown()
    {
        if (!$this->selectedOrderDetails) {
            return null;
        }

        $items = $this->selectedOrderDetails->items;

        $completedItems = $items->where('status', 'completed');
        $completedSubtotal = $completedItems->sum(fn($item) => $item->price * $item->quantity);
        $completedVat = round($completedSubtotal * 0.12, 2);

        $notChargedItems = $items->whereIn('status', ['no_show', 'cancelled']);
        $notChargedSubtotal = $notChargedItems->sum(fn($item) => $item->price * $item->quantity);
        $notChargedVat = round($notChargedSubtotal * 0.12, 2);

        $outstandingItems = $items->whereIn('status', ['pending', 'preparing', 'ready_for_pickup']);
        $outstandingSubtotal = $outstandingItems->sum(fn($item) => $item->price * $item->quantity);
        $outstandingVat = round($outstandingSubtotal * 0.12, 2);

        $originalSubtotal = $items->sum(fn($item) => $item->price * $item->quantity);
        $originalVat = round($originalSubtotal * 0.12, 2);

        $mapItems = function ($collection) {
            return $collection->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'Product Unavailable',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                    'status' => $item->status,
                ];
            })->values()->toArray();
        };

        return [
            'original_subtotal' => $originalSubtotal,
            'original_vat' => $originalVat,
            'original_total' => $originalSubtotal + $originalVat,
            'original_items' => $mapItems($items),

            'charged_subtotal' => $completedSubtotal,
            'charged_vat' => $completedVat,
            'amount_charged' => $completedSubtotal + $completedVat,
            'charged_items' => $mapItems($completedItems),

            'not_charged_subtotal' => $notChargedSubtotal,
            'not_charged_vat' => $notChargedVat,
            'amount_not_charged' => $notChargedSubtotal + $notChargedVat,
            'not_charged_items' => $mapItems($notChargedItems),

            'outstanding_subtotal' => $outstandingSubtotal,
            'outstanding_vat' => $outstandingVat,
            'amount_outstanding' => $outstandingSubtotal + $outstandingVat,
            'outstanding_items' => $mapItems($outstandingItems),
        ];
    }

    public function render()
    {
        return view('livewire.customer.orders')
            ->layout('components.layouts.customer');
    }
}
