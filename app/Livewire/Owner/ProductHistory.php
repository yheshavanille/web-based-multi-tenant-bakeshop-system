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

    public function render()
    {
        return view('livewire.owner.product-history')
            ->layout('components.layouts.owner');
    }
}
