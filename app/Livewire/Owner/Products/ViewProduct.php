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

    public Collection $categories;

    public function mount()
    {
        $shop = Auth::user()->shop;

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();
    }

    public function delete(int $productId)
    {
        Product::findOrFail($productId)->delete();

        session()->flash('message', 'Product deleted successfully.');
    }

    public function render()
    {
        $shop = Auth::user()->shop;

        $query = $shop->products();

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.owner.products.view-product', [
            'products' => $query->get(),
            'categories' => $this->categories,
        ]);
    }
}
