<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BranchProduct;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\ProductEditHistory;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Orders extends Component
{
    public $orders = [];
    public $branch;
    public $selectedStatus = 'all';
    public $showItems = [];
    public $search = '';

    // ✅ Order Details Modal
    public $showDetailsModal = false;
    public $selectedOrderDetails = null;

    public function mount()
    {
        $employee = Auth::user()->employee;
        $this->branch = $employee->branch;
        $this->loadOrders();
    }

    // ✅ UPDATED: Exclude parent orders from employee view
    public function loadOrders()
    {
        $query = Order::where('branch_id', $this->branch->id)
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        // ✅ FIX: Filter by item statuses, not just main order status
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

        $this->orders = $query->get();
    }

    // ✅ Open Order Details Modal
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

    // ✅ Close Order Details Modal
    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrderDetails = null;
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

    public function updateItemStatus($itemId, $status)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('branch_id', $this->branch->id)
                ->where(function ($q) {
                    $q->whereNull('is_parent_order')
                        ->orWhere('is_parent_order', false);
                });
        })->findOrFail($itemId);

        // ✅ BLOCK: If the item was cancelled by customer
        if ($item->status === 'cancelled') {
            if ($item->order->status === 'pending' || ($item->order->status === 'cancelled' && $item->order->cancelled_by === 'customer')) {
                session()->flash('error', 'This item was cancelled by the customer and cannot be updated.');
                $this->loadOrders();
                return;
            }
        }

        if ($item->order->status === 'cancelled' && $item->order->cancelled_by === 'customer') {
            session()->flash('error', 'This order was cancelled by the customer and cannot be updated.');
            $this->loadOrders();
            return;
        }

        $oldStatus = $item->status;
        $item->update(['status' => $status]);

        // ✅ LOG TO PRODUCT EDIT HISTORY for order status changes
        if ($oldStatus !== $status) {
            ProductEditHistory::create([
                'product_id' => $item->product_id,
                'user_id' => Auth::id(),
                'field' => 'order_status',
                'old_value' => ucfirst(str_replace('_', ' ', $oldStatus)),
                'new_value' => ucfirst(str_replace('_', ' ', $status)),
            ]);
        }

        if ($status === 'completed' && $oldStatus !== 'completed') {
            $this->reduceStock($item);
        }

        $order = $item->order;
        $this->recalculateOrderStatus($order);
        $order->refresh();

        // ✅ FIX: Pass product name and item ID when sending notifications
        if ($oldStatus !== $status) {
            $productName = $item->product->name ?? 'Product';

            $customer = $order->customer;
            if ($customer) {
                Notification::send($customer, new OrderStatusUpdatedNotification(
                    $order,
                    $oldStatus,
                    $status,
                    $productName,
                    $item->id
                ));
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

        $this->loadOrders();
        session()->flash('message', 'Item status updated successfully!');
    }

    private function reduceStock($orderItem)
    {
        Log::info('reduceStock called', [
            'branch_id' => $this->branch->id,
            'product_id' => $orderItem->product_id,
            'quantity' => $orderItem->quantity,
        ]);

        $branchProduct = DB::table('branch_product')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $orderItem->product_id)
            ->first();

        if (!$branchProduct) {
            Log::error('BranchProduct not found', [
                'branch_id' => $this->branch->id,
                'product_id' => $orderItem->product_id,
            ]);
            session()->flash('error', 'Stock record not found!');
            return;
        }

        $oldStock = $branchProduct->stock;
        $newStock = max(0, $oldStock - $orderItem->quantity);

        DB::table('branch_product')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $orderItem->product_id)
            ->update(['stock' => $newStock]);

        StockHistory::create([
            'product_id' => $orderItem->product_id,
            'branch_id' => $this->branch->id,
            'user_id' => Auth::id(),
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => 'Order #' . $orderItem->order->order_number . ' - Item marked as completed',
        ]);

        Log::info('Stock reduced and history created', [
            'product_id' => $orderItem->product_id,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'order_number' => $orderItem->order->order_number,
        ]);
    }

    // ✅ UPDATED: Handles mixed statuses properly
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
            $order->update(['status' => 'cancelled', 'payment_status' => 'refunded']);
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
        return view('livewire.employee.orders')
            ->layout('components.layouts.employee');
    }
}
