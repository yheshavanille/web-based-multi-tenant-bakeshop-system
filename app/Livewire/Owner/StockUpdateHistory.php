<?php

namespace App\Livewire\Owner;

use App\Models\InventoryHistory;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockUpdateHistory extends Component
{
    public $search = '';
    public $typeFilter = 'all';     // all | manual | order | stock_in | stock_out | adjustment | order_sale | cancelled | no_show
    public $branchFilter = 'all';
    public $branches = [];
    public $histories = [];

    public function mount()
    {
        $shop = Auth::user()->shop;
        $this->branches = $shop->branches;

        $this->loadHistories();
    }

    public function loadHistories()
    {
        $shop = Auth::user()->shop;

        // ─── Manual movements ──────────────────────────────
        $inventory = collect();
        if (in_array($this->typeFilter, ['all', 'manual', 'stock_in', 'stock_out', 'adjustment'])) {
            $q = InventoryHistory::whereHas('product', function ($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            })->with(['product', 'user', 'branch']);

            if ($this->typeFilter === 'stock_in' || $this->typeFilter === 'stock_out' || $this->typeFilter === 'adjustment') {
                $q->where('type', $this->typeFilter);
            }

            if ($this->branchFilter !== 'all') {
                $q->where('branch_id', $this->branchFilter);
            }

            if (!empty($this->search)) {
                $searchTerm = '%' . $this->search . '%';
                $q->whereHas('product', function ($pq) use ($searchTerm) {
                    $pq->where('name', 'like', $searchTerm);
                });
            }

            $inventory = $q->get()->map(function ($row) {
                return [
                    'kind' => 'inventory',
                    'id' => 'i-' . $row->id,
                    'type' => $row->type,                      // stock_in | stock_out | adjustment
                    'product' => $row->product,
                    'branch' => $row->branch,
                    'user' => $row->user,
                    'quantity' => $row->quantity,
                    'old_stock' => $row->old_stock,
                    'new_stock' => $row->new_stock,
                    'notes' => $row->notes,
                    'created_at' => $row->created_at,
                ];
            });
        }

        // ─── Order-driven movements ────────────────────────
        $orders = collect();
        if (in_array($this->typeFilter, ['all', 'order', 'order_sale', 'cancelled', 'no_show'])) {
            $q = OrderHistory::whereHas('product', function ($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            })->with(['product', 'user', 'branch', 'order']);

            if (in_array($this->typeFilter, ['order_sale', 'cancelled', 'no_show'])) {
                $map = [
                    'order_sale' => 'out',
                    'cancelled' => 'cancelled',
                    'no_show' => 'no_show',
                ];
                $q->where('status', $map[$this->typeFilter]);
            }

            if ($this->branchFilter !== 'all') {
                $q->where('branch_id', $this->branchFilter);
            }

            if (!empty($this->search)) {
                $searchTerm = '%' . $this->search . '%';
                $q->whereHas('product', function ($pq) use ($searchTerm) {
                    $pq->where('name', 'like', $searchTerm);
                });
            }

            $orders = $q->get()->map(function ($row) {
                return [
                    'kind' => 'order',
                    'id' => 'o-' . $row->id,
                    'type' => $row->status,                        // out | cancelled | no_show
                    'product' => $row->product,
                    'branch' => $row->branch,
                    'user' => $row->user,
                    'quantity' => $row->quantity,
                    'old_stock' => $row->old_stock,
                    'new_stock' => $row->new_stock,
                    'notes' => $row->notes,
                    'created_at' => $row->created_at,
                ];
            });
        }

        // ─── Merge and sort by newest first ────────────────
        $this->histories = $inventory
            ->concat($orders)
            ->sortByDesc('created_at')
            ->values();
    }

    public function updatedSearch()
    {
        $this->loadHistories();
    }
    public function updatedTypeFilter()
    {
        $this->loadHistories();
    }
    public function updatedBranchFilter()
    {
        $this->loadHistories();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadHistories();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->typeFilter = 'all';
        $this->branchFilter = 'all';
        $this->loadHistories();
    }

    public function render()
    {
        return view('livewire.owner.stock-update-history')
            ->layout('components.layouts.owner');
    }
}
