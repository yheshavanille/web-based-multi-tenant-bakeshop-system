<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Livewire\Component;

class ShopDetails extends Component
{
    public $shop;
    public $categories;
    public $selectedCategory = 'all';

    public function mount($shopId)
    {
        $this->shop = Shop::with(['user'])->findOrFail($shopId);

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $this->shop->id)
            ->get();
    }

    public function render()
    {
        $products = Product::with('category')
            ->where('shop_id', $this->shop->id)
            ->when($this->selectedCategory !== 'all', function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->get();

        return view('livewire.admin.pages.shops.shop-details', [
            'products' => $products,
            'categories' => $this->categories,
        ]);
    }
}
