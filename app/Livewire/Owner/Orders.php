<?php

namespace App\Livewire\Owner;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Orders extends Component
{
    public $branches = [];
    public $orders = [];
    public $selectedBranch = 'all';
    public $selectedStatus = 'all';
    public $search = '';

    // Order Details Modal
    public $showOrderDetails = false;
    public $selectedOrder = null;

    public function mount()
    {
        $shop = Auth::user()->shop;
        $this->branches = Branch::where('shop_id', $shop->id)
            ->orderBy('name')
            ->get();

        // ✅ Allow deep-linking: /owner/orders?status=pending
        if (request()->has('status')) {
            $this->selectedStatus = request()->get('status');
        }

        $this->loadOrders();
    }

    public function loadOrders()
    {
        $shop = Auth::user()->shop;

        $query = Order::where('shop_id', $shop->id)
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->with(['customer', 'branch', 'items.product'])
            ->orderBy('created_at', 'desc');

        // ✅ Branch filter
        if ($this->selectedBranch !== 'all') {
            $query->where('branch_id', $this->selectedBranch);
        }

        // ✅ Status filter — reuse the same logic as BranchOrders
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

        // ✅ Search
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

                // ✅ Total-with-VAT to match dashboard and BranchOrders
                $adjustedSubtotal = $order->items
                    ->where('status', '!=', 'cancelled')
                    ->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });

                $order->adjusted_subtotal = $adjustedSubtotal;
                $order->adjusted_tax = round($adjustedSubtotal * 0.12, 2);
                $order->adjusted_total = $adjustedSubtotal + $order->adjusted_tax;

                return $order;
            });
    }

    public function updatedSelectedBranch()
    {
        $this->loadOrders();
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

    public function resetFilters()
    {
        $this->selectedBranch = 'all';
        $this->selectedStatus = 'all';
        $this->search = '';
        $this->loadOrders();
    }

    public function viewOrderDetails($orderId)
    {
        $shop = Auth::user()->shop;

        $this->selectedOrder = Order::with(['customer', 'items.product', 'branch'])
            ->where('shop_id', $shop->id)
            ->findOrFail($orderId);

        // ✅ Same total-with-VAT logic as the table
        $adjustedSubtotal = $this->selectedOrder->items
            ->where('status', '!=', 'cancelled')
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });

        $this->selectedOrder->adjusted_subtotal = $adjustedSubtotal;
        $this->selectedOrder->adjusted_tax = round($adjustedSubtotal * 0.12, 2);
        $this->selectedOrder->adjusted_total = $adjustedSubtotal + $this->selectedOrder->adjusted_tax;

        $this->showOrderDetails = true;
    }

    public function closeOrderDetails()
    {
        $this->showOrderDetails = false;
        $this->selectedOrder = null;
    }

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
        return view('livewire.owner.orders')
            ->layout('components.layouts.owner');
    }
}
