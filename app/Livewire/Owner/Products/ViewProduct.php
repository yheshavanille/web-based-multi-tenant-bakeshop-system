<?php

namespace App\Livewire\Owner\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class ViewProduct extends Component
{
    public string $selectedCategory = 'all';
    public $selectedBranchId = null;
    public $showDeleted = false;
    public Collection $categories;

    public function mount($branch = null)
    {
        $shop = Auth::user()->shop;

        $this->selectedBranchId = $branch;

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    public function delete(int $productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        session()->flash('message', '🗑️ Product moved to deleted records.');
    }

    public function restore(int $productId)
    {
        $product = Product::withTrashed()->findOrFail($productId);
        $product->restore();

        session()->flash('message', '✅ Product restored successfully.');
    }

    public function render()
    {
        $shop = Auth::user()->shop;

        $query = $shop->products()->with('branches');

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->selectedBranchId) {
            $query->whereHas('branches', function ($q) {
                $q->where('branch_id', $this->selectedBranchId);
            });
        }

        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        return view('livewire.owner.products.view-product', [
            'products' => $query->get(),
            'categories' => $this->categories,
        ]);
    }
}
