<?php

namespace App\Livewire\Employee;

use App\Models\StockHistory as StockHistoryModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockEditHistory extends Component
{
    public $stockHistories = [];
    public $search = '';
    public $branch;

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
        $query = StockHistoryModel::where('branch_id', $this->branch->id)
            ->with(['product', 'user', 'branch'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            })->orWhere('notes', 'like', $searchTerm);
        }

        $this->stockHistories = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadStockHistories();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadStockHistories();
    }

    public function render()
    {
        return view('livewire.employee.stock-edit-history')
            ->layout('components.layouts.employee');
    }
}
