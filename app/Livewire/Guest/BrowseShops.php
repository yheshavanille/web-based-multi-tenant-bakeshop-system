<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use App\Models\Product;
use App\Models\ServiceReview;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BrowseShops extends Component
{
    public $search = '';
    public $shops = [];
    public $categories = [];

    // Store products per shop separately
    public $shopProducts = [];

    public function mount()
    {
        $this->loadCategories();
        $this->loadShops();
    }

    public function loadCategories()
    {
        $this->categories = Category::all();
    }

    public function loadShops()
    {
        $query = Shop::with(['branches', 'user']);

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        $this->shops = $query->get();

        // Load products for each shop
        foreach ($this->shops as $shop) {
            $this->loadShopProducts($shop->id);
        }
    }

    public function loadShopProducts($shopId)
    {
        $shop = $this->shops->firstWhere('id', $shopId);
        if (!$shop) {
            return;
        }

        // ✅ Simple query - show ALL products with stock
        $products = Product::where('shop_id', $shopId)
            ->whereHas('branches', function ($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['category', 'branches'])
            ->withAvg('productReviews', 'rating')
            ->withCount('productReviews')
            ->limit(4)
            ->get();

        // Store products
        $this->shopProducts[$shopId] = $products;

        // Also store rating on the shop object for the view
        $shop->rating = ServiceReview::where('shop_id', $shopId)->avg('rating') ?? 0;
        $shop->rating_count = ServiceReview::where('shop_id', $shopId)->count();
    }

    public function updatedSearch()
    {
        $this->loadShops();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadShops();
    }

    public function render()
    {
        return view('livewire.guest.browse-shops', [
            'shops' => $this->shops,
            'categories' => $this->categories,
            'shopProducts' => $this->shopProducts,
        ])->layout('components.layouts.guest');
    }
}
