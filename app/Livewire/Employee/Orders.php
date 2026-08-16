<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Orders extends Component
{
    public $orders = [];
    public $branch;
    public $selectedStatus = 'all';
    public $showItems = [];

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

        $this->orders = $query->get();
    }

    public function updatedSelectedStatus()
    {
        $this->loadOrders();
    }

    public function updateItemStatus($itemId, $status)
    {
        $item = OrderItem::whereHas('order', function ($query) {
            $query->where('branch_id', $this->branch->id);
        })->findOrFail($itemId);

        $item->update(['status' => $status]);

        // Recalculate order status
        $order = $item->order;
        $this->recalculateOrderStatus($order);

        $this->loadOrders();
        session()->flash('message', 'Item status updated successfully!');
    }

    // ONLY ONE recalculateOrderStatus METHOD
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
        $totalItems = count($itemStatuses);

        if ($completedCount === $totalItems) {
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
