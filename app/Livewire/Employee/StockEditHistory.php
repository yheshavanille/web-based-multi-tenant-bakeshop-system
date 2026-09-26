<?php

namespace App\Livewire\Employee;

use App\Models\InventoryHistory;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockEditHistory extends Component
{
    public $search = '';
    public $scopeFilter = 'all';   // all | manual | mine
    public $branch;
    public $stockHistories = [];

    public function mount()
    {
        $employee = Auth::user()->employee;
        if (!$employee || $employee->role !== 'inventory_manager') {
            abort(403, 'Unauthorized access.');
        }

        $this->branch = $employee->branch;
        $this->loadStockHistories();
    }

    public function loadStockHistories()
    {
        $employeeId = Auth::id();

        $inventory = collect();
        $orders = collect();

        // ─── Manual movements ──────────────────────────
        if (in_array($this->scopeFilter, ['all', 'manual', 'mine'])) {
            $q = InventoryHistory::where('branch_id', $this->branch->id)
                ->with(['product', 'user', 'branch']);

            if ($this->scopeFilter === 'manual') {
                // show all manual movements by anyone in this branch
                // (already filtered by branch above)
            } elseif ($this->scopeFilter === 'mine') {
                $q->where('user_id', $employeeId);
            }

            if (!empty($this->search)) {
                $searchTerm = '%' . $this->search . '%';
                $q->where(function ($qq) use ($searchTerm) {
                    $qq->whereHas('product', function ($pq) use ($searchTerm) {
                        $pq->where('name', 'like', $searchTerm);
                    })->orWhere('notes', 'like', $searchTerm);
                });
            }

            $inventory = $q->get()->map(function ($row) {
                return (object) [
                    'kind' => 'inventory',
                    'type' => $row->type,
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

        // ─── Order movements (hidden if "mine" is selected) ────
        if (in_array($this->scopeFilter, ['all'])) {
            $q = OrderHistory::where('branch_id', $this->branch->id)
                ->with(['product', 'user', 'branch']);

            if (!empty($this->search)) {
                $searchTerm = '%' . $this->search . '%';
                $q->where(function ($qq) use ($searchTerm) {
                    $qq->whereHas('product', function ($pq) use ($searchTerm) {
                        $pq->where('name', 'like', $searchTerm);
                    })->orWhere('notes', 'like', $searchTerm);
                });
            }

            $orders = $q->get()->map(function ($row) {
                return (object) [
                    'kind' => 'order',
                    'type' => $row->status,
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

        $this->stockHistories = $inventory
            ->concat($orders)
            ->sortByDesc('created_at')
            ->values();
    }

    public function updatedSearch()
    {
        $this->loadStockHistories();
    }
    public function updatedScopeFilter()
    {
        $this->loadStockHistories();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadStockHistories();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->scopeFilter = 'all';
        $this->loadStockHistories();
    }

    public function render()
    {
        return view('livewire.employee.stock-edit-history')
            ->layout('components.layouts.employee');
    }
}
