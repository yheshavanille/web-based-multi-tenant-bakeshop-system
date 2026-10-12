<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\BranchProduct;
use App\Models\Product;
use App\Models\ProductEditHistory;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Notifications\StockReviewNeededNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $branch;
    public $selectedStatus = 'all';
    public $showItems = [];
    public $search = '';

    public $showDetailsModal = false;
    public $selectedOrderDetails = null;

    public function mount()
    {
        $employee = Auth::user()->employee;
        $this->branch = $employee->branch;

        if (request()->has('status')) {
            $this->selectedStatus = request()->get('status');
        }
    }

    private function buildOrdersQuery()
    {
        $query = Order::where('branch_id', $this->branch->id)
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->with(['customer', 'items.product'])
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
            } elseif ($this->selectedStatus === 'partially_completed') {
                $query->where(function ($q) {
                    $q->whereHas('items', function ($q2) {
                        $q2->where('status', 'completed');
                    });
                })->where(function ($q) {
                    $q->whereHas('items', function ($q2) {
                        $q2->whereIn('status', ['pending', 'preparing', 'ready_for_pickup', 'no_show', 'cancelled']);
                    });
                })->where('status', 'partially_completed');
            } else {
                $query->where('status', $this->selectedStatus);
            }
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', $searchTerm)
                    ->orWhereHas('customer', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                    })
                    ->orWhereHas('items.product', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', $searchTerm);
                    });
            });
        }

        return $query;
    }

    public function openDetailsModal($orderId)
    {
        $this->selectedOrderDetails = Order::with(['customer', 'items.product', 'branch'])
            ->where('branch_id', $this->branch->id)
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->where('id', $orderId)
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

    public function updateItemStatus($itemId, $status)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('branch_id', $this->branch->id)
                ->where(function ($q) {
                    $q->whereNull('is_parent_order')
                        ->orWhere('is_parent_order', false);
                });
        })->findOrFail($itemId);

        if ($item->status === 'cancelled') {
            if ($item->order->status === 'pending' || ($item->order->status === 'cancelled' && $item->order->cancelled_by === 'customer')) {
                session()->flash('error', 'This item was cancelled by the customer and cannot be updated.');
                return;
            }
        }

        if ($item->order->status === 'cancelled' && $item->order->cancelled_by === 'customer') {
            session()->flash('error', 'This order was cancelled by the customer and cannot be updated.');
            return;
        }

        $oldStatus = $item->status;
        $item->update(['status' => $status]);

        if ($oldStatus !== $status) {
            ProductEditHistory::create([
                'product_id' => $item->product_id,
                'user_id' => Auth::id(),
                'field' => 'order_status',
                'old_value' => ucfirst(str_replace('_', ' ', $oldStatus)),
                'new_value' => ucfirst(str_replace('_', ' ', $status)),
            ]);
        }

        $wasAlreadyFinal = in_array($oldStatus, ['cancelled', 'no_show']);
        $isNowFinal = in_array($status, ['cancelled', 'no_show']);

        if (!$wasAlreadyFinal && $isNowFinal) {
            $this->restoreStockForItem($item, $status);
        }

        $order = $item->order;
        $this->recalculateOrderStatus($order);
        $order->refresh();

        if ($oldStatus !== $status && in_array($status, ['cancelled', 'no_show'])) {
            $reason = $status === 'no_show' ? 'no_show' : 'cancelled_by_staff';
            $this->notifyStockReview($order, $item, $reason);
        }

        if ($oldStatus !== $status) {
            $productName = $item->product->name ?? 'Product';

            \Log::info('[EMAIL DEBUG] Reached notification block', [
                'item_id' => $item->id,
                'old_status' => $oldStatus,
                'new_status' => $status,
            ]);

            $customer = $order->customer;

            \Log::info('[EMAIL DEBUG] Customer lookup', [
                'customer_found' => $customer ? 'yes' : 'no',
                'customer_id' => $customer?->id,
                'customer_email' => $customer?->email,
            ]);

            if ($customer) {
                Notification::send($customer, new OrderStatusUpdatedNotification(
                    $order,
                    $oldStatus,
                    $status,
                    $productName,
                    $item->id
                ));

                \Log::info('[EMAIL DEBUG] Bell notification sent');

                if (in_array($status, ['ready_for_pickup', 'completed'])) {
                    \Log::info('[EMAIL DEBUG] Entering email block', [
                        'status' => $status,
                    ]);

                    try {
                        Notification::send($customer, new \App\Notifications\OrderItemEmailNotification(
                            $order,
                            $item,
                            $status
                        ));

                        \Log::info('[EMAIL DEBUG] Email sent successfully', [
                            'item_id' => $item->id,
                            'email' => $customer->email,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('[EMAIL DEBUG] Email FAILED', [
                            'item_id' => $item->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                    }
                } else {
                    \Log::info('[EMAIL DEBUG] Skipped email — status not in array', [
                        'status' => $status,
                    ]);
                }
            }

            $owner = $order->shop->user;
            if ($owner && $owner->id !== ($customer->id ?? null)) {
                Notification::send($owner, new OrderStatusUpdatedNotification(
                    $order,
                    $oldStatus,
                    $status,
                    $productName,
                    $item->id
                ));
            }
        }

        session()->flash('message', 'Item status updated successfully!');
$this->dispatch('$refresh');
}

    private function recalculateOrderStatus($order)
    {
        $itemStatuses = $order->items()->pluck('status')->toArray();

        $pendingCount = count(array_filter($itemStatuses, function ($s) {
            return $s === 'pending';
        }));
        $readyCount = count(array_filter($itemStatuses, function ($s) {
            return $s === 'ready_for_pickup';
        }));
        $completedCount = count(array_filter($itemStatuses, function ($s) {
            return $s === 'completed';
        }));
        $cancelledCount = count(array_filter($itemStatuses, function ($s) {
            return in_array($s, ['cancelled', 'no_show']);
        }));
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

        if ($completedCount > 0 && ($pendingCount > 0 || $readyCount > 0 || $cancelledCount > 0)) {
            $order->update(['status' => 'partially_completed', 'payment_status' => 'partially_paid']);
            return;
        }

        if ($pendingCount > 0) {
            $order->update(['status' => 'pending', 'payment_status' => 'pending']);
            return;
        }

        if ($readyCount > 0) {
            $order->update(['status' => 'ready_for_pickup', 'payment_status' => 'pending']);
            return;
        }

        $order->update(['status' => 'preparing', 'payment_status' => 'pending']);
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

        return view('livewire.employee.orders', [
            'orders' => $orders,
        ])->layout('components.layouts.employee');
    }
}
