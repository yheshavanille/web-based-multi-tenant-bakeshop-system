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
    public $products = [];
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
        $productQuery = Product::with(['shop', 'category', 'branches']);

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });

            $productQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            })
                ->whereHas('shop', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->whereHas('branches', function ($q) {
                    $q->where('branch_product.stock', '>', 0)
                        ->where('branches.is_active', true);
                });

            $this->products = $productQuery->limit(8)->get();
        } else {
            $this->products = collect();
        }

        $this->shops = $query->get();

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

        // ✅ Filter the product reviews to only show approved ones
        $products = Product::where('shop_id', $shopId)
            ->whereHas('branches', function ($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['category', 'branches'])
            ->withAvg(['productReviews' => function ($q) {
                $q->whereIn('moderation_status', ['visible', 'kept']);
            }], 'rating')
            ->withCount(['productReviews' => function ($q) {
                $q->whereIn('moderation_status', ['visible', 'kept']);
            }])
            ->limit(4)
            ->get();

        $this->shopProducts[$shopId] = $products;

        // ✅ Filter the shop's service rating to only show approved ones
        $shop->rating = ServiceReview::where('shop_id', $shopId)
            ->whereIn('moderation_status', ['visible', 'kept'])
            ->avg('rating') ?? 0;

        $shop->rating_count = ServiceReview::where('shop_id', $shopId)
            ->whereIn('moderation_status', ['visible', 'kept'])
            ->count();
    }

    public function updatedSearch()
    {
        $this->loadShops();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->products = collect();
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
