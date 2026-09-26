<?php

namespace App\Livewire\Owner;

use App\Models\ProductEditHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductHistory extends Component
{
    public $histories = [];
    public $search = '';

    public function mount()
    {
        $this->loadHistories();
    }

    public function loadHistories()
    {
        $shop = Auth::user()->shop;

        $query = ProductEditHistory::whereHas('product', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with(['product', 'user']);

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        $this->histories = $query->orderBy('created_at', 'desc')->get();
    }

    public function updatedSearch()
    {
        $this->loadHistories();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadHistories();
    }

    /**
     * ✅ Clean old-format values for display.
     * Old stock rows used to store "Branch Name: 23" — we strip that.
     * Old branch rows used to store "Branch Name (stock: 5)" — we strip too.
     */
    public function cleanValue($field, $value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $value = (string) $value;

        // Stock field: "Something Here: 23" → "23"
        if ($field === 'stock' && preg_match('/:\s*(-?\d+)\s*$/', $value, $m)) {
            return $m[1];
        }

        // Branch field: "Branch Name (stock: 5)" → "Stock: 5"
        if ($field === 'branch' && preg_match('/\(stock:\s*(-?\d+)\)/', $value, $m)) {
            return 'Stock: ' . $m[1];
        }

        return $value;
    }

    public function render()
    {
        return view('livewire.owner.product-history')
            ->layout('components.layouts.owner');
    }
}
