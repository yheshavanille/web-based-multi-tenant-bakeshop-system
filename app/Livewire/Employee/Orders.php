<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BranchProduct;
use App\Models\Product;
use App\Models\StockHistory;
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

    public function loadOrders()
    {
        $query = Order::where('branch_id', $this->branch->id)
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
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
            $query->where('branch_id', $this->branch->id);
        })->findOrFail($itemId);

        if ($item->status === 'cancelled') {
            session()->flash('error', 'This item was cancelled by the customer and cannot be updated.');
            $this->loadOrders();
            return;
        }

        if ($item->order->status === 'cancelled') {
            session()->flash('error', 'This order was cancelled and cannot be updated.');
            $this->loadOrders();
            return;
        }

        $oldStatus = $item->status;
        $item->update(['status' => $status]);

        if ($status === 'completed' && $oldStatus !== 'completed') {
            $this->reduceStock($item);
        }

        $order = $item->order;

        // Recalculate order status
        $this->recalculateOrderStatus($order);

        // ✅ REFRESH ORDER TO GET UPDATED STATUS
        $order->refresh();

        // ✅ UPDATE PAYMENT STATUS IF ORDER IS COMPLETED
        if ($order->status === 'completed') {
            $order->update(['payment_status' => 'paid']);
        }

        // ✅ SEND NOTIFICATION TO CUSTOMER AND OWNER
        if ($oldStatus !== $status) {
            $customer = $order->customer;
            if ($customer) {
                Notification::send($customer, new OrderStatusUpdatedNotification($order, $oldStatus, $status));
            }

            // ✅ ALSO NOTIFY THE SHOP OWNER
            $owner = $order->shop->user;
            if ($owner && $owner->id !== ($customer->id ?? null)) {
                Notification::send($owner, new OrderStatusUpdatedNotification($order, $oldStatus, $status));
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
            return $s === 'cancelled';
        }));
        $totalItems = count($itemStatuses);

        if ($cancelledCount === $totalItems) {
            $order->update(['status' => 'cancelled']);
        } elseif ($completedCount === $totalItems - $cancelledCount) {
            $order->update(['status' => 'completed']);
        } elseif ($pendingCount > 0) {
            $order->update(['status' => 'pending']);
        } elseif ($readyCount > 0) {
            $order->update(['status' => 'ready_for_pickup']);
        } else {
            $order->update(['status' => 'preparing']);
        }
    }

    public function render()
    {
        return view('livewire.employee.orders')
            ->layout('components.layouts.employee');
    }
}
