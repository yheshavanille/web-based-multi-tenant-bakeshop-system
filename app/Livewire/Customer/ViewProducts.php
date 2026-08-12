<?php

namespace App\Livewire\Customer;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewProducts extends Component
{
    public $shopId;
    public $selectedCategory = 'all';

    public function mount($shopId)
    {
        $this->shopId = $shopId;
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        $this->dispatch('cartUpdated');
        session()->flash('message', 'Product added to cart!');
    }

    public function render()
    {
        $shop = Shop::with('user')->findOrFail($this->shopId);

        $categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();

        $productsQuery = Product::where('shop_id', $this->shopId);

        if ($this->selectedCategory !== 'all') {
            $productsQuery->where('category_id', $this->selectedCategory);
        }

        return view('livewire.customer.view-products', [
            'shop' => $shop,
            'products' => $productsQuery->get(),
            'categories' => $categories,
        ])->layout('components.layouts.customer');
    }
}
