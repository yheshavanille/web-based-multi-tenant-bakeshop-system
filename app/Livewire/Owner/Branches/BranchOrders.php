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
    public $selectedStatus = 'all';

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
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedStatus !== 'all') {
            if ($this->selectedStatus === 'no_show') {
                $query->whereHas('items', function ($q) {
                    $q->where('status', 'no_show');
                });
            } elseif ($this->selectedStatus === 'completed') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'pending', 'preparing', 'ready_for_pickup']);
                })->where('status', 'completed');
            } elseif ($this->selectedStatus === 'ready_for_pickup') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'pending', 'preparing', 'completed']);
                })->where('status', 'ready_for_pickup');
            } elseif ($this->selectedStatus === 'preparing') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'pending', 'ready_for_pickup', 'completed']);
                })->where('status', 'preparing');
            } elseif ($this->selectedStatus === 'pending') {
                $query->whereDoesntHave('items', function ($q) {
                    $q->whereIn('status', ['no_show', 'cancelled', 'preparing', 'ready_for_pickup', 'completed']);
                })->where('status', 'pending');
            } elseif ($this->selectedStatus === 'cancelled') {
                $query->where('status', 'cancelled');
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

        $this->orders = $query->get()
            ->map(function ($order) {
                $itemCount = $order->items->count();
                $cancelledCount = $order->items->where('status', 'cancelled')->count();
                $completedCount = $order->items->where('status', 'completed')->count();
                $pendingCount = $order->items->where('status', 'pending')->count();
                $preparingCount = $order->items->where('status', 'preparing')->count();
                $readyCount = $order->items->where('status', 'ready_for_pickup')->count();
                $noShowCount = $order->items->where('status', 'no_show')->count();

                $statusParts = [];
                if ($completedCount > 0) $statusParts[] = $completedCount . ' completed';
                if ($cancelledCount > 0) $statusParts[] = $cancelledCount . ' cancelled';
                if ($pendingCount > 0) $statusParts[] = $pendingCount . ' pending';
                if ($preparingCount > 0) $statusParts[] = $preparingCount . ' preparing';
                if ($readyCount > 0) $statusParts[] = $readyCount . ' ready';
                if ($noShowCount > 0) $statusParts[] = $noShowCount . ' no show';

                $order->status_summary = !empty($statusParts)
                    ? implode(', ', $statusParts)
                    : ucfirst(str_replace('_', ' ', $order->status));

                $order->item_count = $itemCount;
                $order->cancelled_count = $cancelledCount;
                $order->completed_count = $completedCount;
                $order->pending_count = $pendingCount;
                $order->no_show_count = $noShowCount;

                $order->adjusted_total = $order->items
                    ->where('status', '!=', 'cancelled')
                    ->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });

                return $order;
            });
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

    public function viewOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['customer', 'items.product', 'branch'])
            ->findOrFail($orderId);

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

    // ✅ NEW: Calculate the 4-section breakdown with product lists
    public function getBreakdown()
    {
        if (!$this->selectedOrder) {
            return null;
        }

        $items = $this->selectedOrder->items;

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
        return view('livewire.owner.branches.branch-orders')
            ->layout('components.layouts.owner');
    }
}
