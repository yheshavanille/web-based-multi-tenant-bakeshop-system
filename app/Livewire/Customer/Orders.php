<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\ServiceReview;
use App\Models\ProductReview;
use App\Notifications\StockReviewNeededNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public const MAX_EDITS = 2;

    public const CANCEL_REASONS = [
        'Ordered by mistake',
        'Changed my mind',
        'Found a better price elsewhere',
        'Pickup time is not convenient',
        'Wrong branch selected',
    ];

    public $selectedStatus = 'all';
    public $search = '';
    public $showReviewModal = false;
    public $selectedOrder = null;
    public $serviceRating = 0;
    public $employeeRating = 0;
    public $serviceReviewText = '';
    public $productRatings = [];
    public $productReviews = [];

    public bool $reviewReadOnly = false;

    public $showDetailsModal = false;
    public $selectedOrderDetails = null;

    public $showReviewDetailsModal = false;
    public $selectedReviewOrder = null;

    // ✅ Cancel modal state (client-side via Alpine)
    public $cancelTarget = null;
    public $cancelTargetId = null;
    public $cancelReason = '';
    public $cancelOtherText = '';
    public $cancelSummary = null;

    // ✅ Reset pagination when filters change
    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    private function buildOrdersQuery()
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

        return $query;
    }

    private function bannedFromReviewing(): bool
    {
        $user = Auth::user();

        if ($user && $user->isReviewBanned()) {
            session()->flash(
                'error',
                'Your ability to leave reviews has been revoked. Reason: '
                    . ($user->review_ban_reason ?: 'Violation of community guidelines')
                    . '. Please contact support if you believe this was a mistake.'
            );
            return true;
        }

        return false;
    }

    private function notifyStockReview(Order $order, OrderItem $item, string $reason): void
    {
        $inventoryManagers = \App\Models\Employee::where('shop_id', $order->shop_id)
            ->where('role', 'inventory_manager')
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();

        $owner = $order->shop->user ?? null;

        $recipients = $inventoryManagers;
        if ($owner) {
            $recipients = $recipients->push($owner);
        }

        if ($recipients->count() > 0) {
            Notification::send($recipients, new StockReviewNeededNotification($order, $item, $reason));
        }
    }

    private function restoreStockForItem(OrderItem $item, string $newStatus): void
    {
        $branchProduct = DB::table('branch_product')
            ->where('branch_id', $item->branch_id)
            ->where('product_id', $item->product_id)
            ->first();

        if (!$branchProduct) {
            return;
        }

        $oldStock = (int) $branchProduct->stock;
        $newStock = $oldStock + (int) $item->quantity;

        DB::table('branch_product')
            ->where('branch_id', $item->branch_id)
            ->where('product_id', $item->product_id)
            ->update(['stock' => $newStock]);

        OrderHistory::create([
            'order_id' => $item->order_id,
            'order_item_id' => $item->id,
            'product_id' => $item->product_id,
            'branch_id' => $item->branch_id,
            'user_id' => Auth::id(),
            'status' => $newStatus,
            'quantity' => (int) $item->quantity,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => 'Order #' . $item->order->order_number . ' — ' . $newStatus . ', stock restored',
        ]);
    }

    #[Renderless]
    public function openCancelOrderModal($orderId)
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

        $this->cancelTarget = 'order';
        $this->cancelTargetId = $order->id;
        $this->cancelSummary = 'Order #' . $order->order_number;
        $this->cancelReason = '';
        $this->cancelOtherText = '';

        $this->dispatch('open-cancel-modal');
    }

    #[Renderless]
    public function openCancelItemModal($itemId)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        })->with('product')->find($itemId);

        if (!$item) {
            session()->flash('error', 'Item not found.');
            return;
        }

        if ($item->status !== 'pending') {
            session()->flash('error', 'Only pending items can be cancelled.');
            return;
        }

        $this->cancelTarget = 'item';
        $this->cancelTargetId = $item->id;
        $this->cancelSummary = $item->product->name ?? 'Item';
        $this->cancelReason = '';
        $this->cancelOtherText = '';

        $this->dispatch('open-cancel-modal');
    }

    public function closeCancelModal()
    {
        $this->resetCancelModal();
    }

    private function resetCancelModal()
    {
        $this->cancelTarget = null;
        $this->cancelTargetId = null;
        $this->cancelReason = '';
        $this->cancelOtherText = '';
        $this->cancelSummary = null;
        $this->resetErrorBag(['cancelReason', 'cancelOtherText']);
    }

    // ✅ Accepts the reason directly from Alpine (fixes async race)
    public function submitCancellation($reasonFromClient = null)
    {
        if ($reasonFromClient) {
            $this->cancelReason = $reasonFromClient;
        }

        $rules = [
            'cancelReason' => 'required|string|max:100',
        ];

        if ($this->cancelReason === 'Other reason') {
            $rules['cancelOtherText'] = 'required|string|max:100';
        }

        $messages = [
            'cancelReason.required' => 'Please select a reason for cancelling.',
            'cancelOtherText.required' => 'Please specify your reason.',
        ];

        $this->validate($rules, $messages);

        $finalReason = $this->cancelReason === 'Other reason'
            ? $this->cancelOtherText
            : $this->cancelReason;

        $finalReason = mb_substr($finalReason, 0, 100);

        if ($this->cancelTarget === 'order') {
            $this->cancelOrder($this->cancelTargetId, $finalReason);
        } elseif ($this->cancelTarget === 'item') {
            $this->cancelItem($this->cancelTargetId, $finalReason);
        }

        $this->resetCancelModal();
        $this->dispatch('close-cancel-modal');
    }

    public function cancelOrder($orderId, $reason = null)
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
            if ($item->status === 'pending') {
                $item->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => $reason,
                ]);

                $this->restoreStockForItem($item, 'cancelled');
                $this->notifyStockReview($order, $item, 'cancelled_by_customer');
            }
        }

        $paymentStatus = $order->isEPayment() ? 'refunded' : 'cancelled';
        $order->update([
            'status' => 'cancelled',
            'payment_status' => $paymentStatus,
            'cancelled_by' => 'customer',
            'cancellation_reason' => $reason,
        ]);

        session()->flash('message', 'Order cancelled successfully.');
    }

    public function cancelItem($itemId, $reason = null)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        })->findOrFail($itemId);

        if ($item->status !== 'pending') {
            session()->flash('error', 'Only pending items can be cancelled.');
            return;
        }

        $item->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        $this->restoreStockForItem($item, 'cancelled');
        $this->notifyStockReview($item->order, $item, 'cancelled_by_customer');

        $order = $item->order;
        $this->recalculateOrderStatus($order);

        if ($order->status === 'cancelled') {
            $order->update(['cancelled_by' => 'customer']);
        }

        session()->flash('message', 'Item cancelled successfully.');
    }

    private function recalculateOrderStatus($order)
    {
        $itemStatuses = $order->items()->pluck('status')->toArray();

        $pendingCount = count(array_filter($itemStatuses, fn($s) => $s === 'pending'));
        $completedCount = count(array_filter($itemStatuses, fn($s) => $s === 'completed'));
        $cancelledCount = count(array_filter($itemStatuses, fn($s) => in_array($s, ['cancelled', 'no_show'])));
        $totalItems = count($itemStatuses);

        if ($cancelledCount === $totalItems) {
            $paymentStatus = $order->isEPayment() ? 'refunded' : 'cancelled';
            $order->update(['status' => 'cancelled', 'payment_status' => $paymentStatus]);
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
        if ($this->bannedFromReviewing()) {
            return;
        }

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

        $hasCompletedItem = $this->selectedOrder->items->contains(fn($item) => $item->status === 'completed');

        if (!$hasCompletedItem) {
            session()->flash('error', 'You can only review orders with completed items.');
            return;
        }

        $this->showReviewModal = true;
        $this->reviewReadOnly = false;
        $this->serviceRating = 0;
        $this->employeeRating = 0;
        $this->serviceReviewText = '';
        $this->productRatings = [];
        $this->productReviews = [];
    }

    public function openEditReviewModal($orderId)
    {
        if ($this->bannedFromReviewing()) {
            return;
        }

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
            if ($serviceReview->edit_count >= self::MAX_EDITS) {
                session()->flash('error', 'You have reached the maximum number of edits for this review (' . self::MAX_EDITS . ' edits).');
                return;
            }

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
        $this->reviewReadOnly = false;
    }

    public function viewLockedReview($orderId)
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
        if (!$serviceReview) {
            session()->flash('error', 'No review found for this order.');
            return;
        }

        $this->serviceRating = $serviceReview->rating;
        $this->employeeRating = $serviceReview->employee_rating;
        $this->serviceReviewText = $serviceReview->review;

        $existingProductReviews = ProductReview::where('order_id', $orderId)->get();
        foreach ($existingProductReviews as $review) {
            $this->productRatings[$review->product_id] = $review->rating;
            $this->productReviews[$review->product_id] = $review->review;
        }

        $this->showReviewModal = true;
        $this->reviewReadOnly = true;
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->selectedOrder = null;
        $this->reviewReadOnly = false;
    }

    // ✅ Cancelled items now visible in the modal
    public function openDetailsModal($orderId)
    {
        $this->selectedOrderDetails = Order::where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->with(['items.product', 'shop', 'branch'])
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
        if ($this->reviewReadOnly) return;
        if ($type === 'service') {
            $this->serviceRating = $rating;
        } elseif ($type === 'employee') {
            $this->employeeRating = $rating;
        }
    }

    public function setProductRating($productId, $rating)
    {
        if ($this->reviewReadOnly) return;
        $this->productRatings[$productId] = $rating;
    }

    public function submitReview()
    {
        if ($this->bannedFromReviewing()) {
            return;
        }

        if ($this->reviewReadOnly) {
            session()->flash('error', 'This review is locked and cannot be edited.');
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

        try {
            $existingServiceReview = ServiceReview::where('order_id', $this->selectedOrder->id)
                ->where('customer_id', Auth::id())
                ->first();

            $isEdit = $existingServiceReview !== null;

            if ($isEdit && $existingServiceReview->edit_count >= self::MAX_EDITS) {
                session()->flash('error', 'You have reached the maximum number of edits for this review (' . self::MAX_EDITS . ' edits).');
                $this->closeReviewModal();
                return;
            }

            if ($isEdit) {
                $existingServiceReview->update([
                    'rating' => $this->serviceRating,
                    'employee_rating' => $this->employeeRating,
                    'review' => $this->serviceReviewText,
                    'edit_count' => $existingServiceReview->edit_count + 1,
                ]);
                $serviceReview = $existingServiceReview;
            } else {
                $serviceReview = ServiceReview::create([
                    'order_id' => $this->selectedOrder->id,
                    'customer_id' => Auth::id(),
                    'shop_id' => $this->selectedOrder->shop_id,
                    'branch_id' => $this->selectedOrder->branch_id,
                    'rating' => $this->serviceRating,
                    'employee_rating' => $this->employeeRating,
                    'review' => $this->serviceReviewText,
                    'edit_count' => 0,
                ]);
            }

            Log::info('Service review saved', ['service_review' => $serviceReview, 'is_edit' => $isEdit]);

            foreach ($this->productRatings as $productId => $rating) {
                if ($rating > 0) {
                    $existingProductReview = ProductReview::where('order_id', $this->selectedOrder->id)
                        ->where('customer_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

                    $isProductEdit = $existingProductReview !== null;

                    if ($isProductEdit && $existingProductReview->edit_count >= self::MAX_EDITS) {
                        continue;
                    }

                    if ($isProductEdit) {
                        $existingProductReview->update([
                            'rating' => $rating,
                            'review' => $this->productReviews[$productId] ?? null,
                            'edit_count' => $existingProductReview->edit_count + 1,
                        ]);
                        $productReview = $existingProductReview;
                    } else {
                        $productReview = ProductReview::create([
                            'order_id' => $this->selectedOrder->id,
                            'customer_id' => Auth::id(),
                            'product_id' => $productId,
                            'shop_id' => $this->selectedOrder->shop_id,
                            'rating' => $rating,
                            'review' => $this->productReviews[$productId] ?? null,
                            'edit_count' => 0,
                        ]);
                    }

                    Log::info('Product review saved', [
                        'product_id' => $productId,
                        'product_review' => $productReview,
                        'is_edit' => $isProductEdit,
                    ]);
                }
            }

            $this->selectedOrder->update([
                'service_review' => true,
            ]);

            $message = $isEdit
                ? 'Review updated successfully!'
                : 'Review submitted successfully!';

            session()->flash('message', $message);
            $this->closeReviewModal();
        } catch (\Exception $e) {
            Log::error('Review submission failed', [
                'order_id' => $this->selectedOrder->id,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to submit review. Please try again.');
        }
    }

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
        $orders = $this->buildOrdersQuery()->paginate(15);

        return view('livewire.customer.orders', [
            'orders' => $orders,
        ])->layout('components.layouts.customer');
    }
}
