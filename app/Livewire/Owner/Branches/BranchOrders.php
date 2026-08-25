<?php

namespace App\Livewire\Owner\Branches;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BranchOrders extends Component
{
    public $branch;
    public $orders = [];
    public $showOrderDetails = false;
    public $selectedOrder = null;
    public $search = '';

    public function mount($branchId)
    {
        $this->branch = Branch::with('shop')->findOrFail($branchId);

        if ($this->branch->shop->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = Order::where('branch_id', $this->branch->id)
            ->where('status', 'completed')
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Apply search filter
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

        $this->orders = $query->get()
            ->map(function ($order) {
                $itemCount = $order->items->count();
                $cancelledCount = $order->items->where('status', 'cancelled')->count();
                $completedCount = $order->items->where('status', 'completed')->count();
                $pendingCount = $order->items->where('status', 'pending')->count();
                $preparingCount = $order->items->where('status', 'preparing')->count();
                $readyCount = $order->items->where('status', 'ready_for_pickup')->count();

                $statusParts = [];
                if ($completedCount > 0) $statusParts[] = $completedCount . ' completed';
                if ($cancelledCount > 0) $statusParts[] = $cancelledCount . ' cancelled';
                if ($pendingCount > 0) $statusParts[] = $pendingCount . ' pending';
                if ($preparingCount > 0) $statusParts[] = $preparingCount . ' preparing';
                if ($readyCount > 0) $statusParts[] = $readyCount . ' ready';

                $order->status_summary = implode(', ', $statusParts);
                $order->item_count = $itemCount;
                $order->cancelled_count = $cancelledCount;
                $order->completed_count = $completedCount;
                $order->pending_count = $pendingCount;

                // ✅ Calculate adjusted total (exclude cancelled items)
                $order->adjusted_total = $order->items
                    ->where('status', '!=', 'cancelled')
                    ->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });

                return $order;
            });
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

    public function viewOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['customer', 'items.product', 'branch'])
            ->findOrFail($orderId);

        // ✅ Calculate adjusted total for modal
        $this->selectedOrder->adjusted_total = $this->selectedOrder->items
            ->where('status', '!=', 'cancelled')
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });

        $this->showOrderDetails = true;
    }

    public function closeOrderDetails()
    {
        $this->showOrderDetails = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        return view('livewire.owner.branches.branch-orders')
            ->layout('components.layouts.owner');
    }
}
