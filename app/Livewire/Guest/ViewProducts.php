<?php

namespace App\Livewire\Guest;

use App\Models\Branch;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ServiceReview;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewProducts extends Component
{
    public $shopId;
    public $selectedBranchId;
    public $selectedCategory = 'all';
    public $shop;
    public $branches = [];
    public $products = [];
    public $categories = [];
    public $bestSellers = [];
    public $search = '';

    public $showReviewModal = false;
    public $selectedProduct = null;
    public $productReviews = [];
    public $averageRating = 0;

    // ✅ Flag to show login modal
    public $showLoginModal = false;

    public function mount($shopId, $branch = null)
    {
        $this->shopId = $shopId;
        $this->shop = Shop::with('user')->findOrFail($shopId);

        $this->branches = Branch::where('shop_id', $shopId)
            ->where('is_active', true)
            ->get();

        if ($branch) {
            $this->selectedBranchId = (int) $branch;
        } else {
            if ($this->branches->isNotEmpty()) {
                $this->selectedBranchId = $this->branches->first()->id;
            }
        }

        $this->loadCategories();
        $this->loadProducts();
        $this->loadBestSellers();
    }

    public function selectBranch($branchId)
    {
        $this->selectedBranchId = $branchId;
        $this->loadProducts();
        $this->loadBestSellers();
    }

    public function loadCategories()
    {
        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $this->shopId)
            ->get();
    }

    public function loadProducts()
    {
        if (!$this->selectedBranchId) {
            $this->products = collect();
            return;
        }

        $branch = Branch::find($this->selectedBranchId);
        if (!$branch) {
            $this->products = collect();
            return;
        }

        $query = Product::where('shop_id', $this->shopId)
            ->whereHas('branches', function ($query) use ($branch) {
                $query->where('branch_id', $branch->id)
                    ->where('stock', '>', 0);
            })
            ->with(['category', 'branches'])
            ->withAvg('productReviews', 'rating');

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        $this->products = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadProducts();
    }

    public function loadBestSellers()
    {
        $branchId = $this->selectedBranchId;

        if (!$branchId) {
            $this->bestSellers = collect();
            return;
        }

        $this->bestSellers = OrderItem::whereHas('order', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId)
                ->where('status', 'completed');
        })
            ->whereHas('product', function ($query) use ($branchId) {
                $query->whereHas('branches', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(3)
            ->get();
    }

    // ✅ FIXED: Reload best sellers when category changes
    public function updatedSelectedCategory()
    {
        $this->loadProducts();
        $this->loadBestSellers();
    }

    public function getStock($productId)
    {
        $branch = Branch::find($this->selectedBranchId);
        if (!$branch) return 0;

        $pivot = $branch->products()->where('product_id', $productId)->first();
        return $pivot ? $pivot->pivot->stock : 0;
    }

    // ✅ Guest addToCart - shows login modal instead of adding to cart
    public function addToCart($productId)
    {
        // ✅ Show login modal for guests
        $this->showLoginModal = true;
    }

    // ✅ Close login modal
    public function closeLoginModal()
    {
        $this->showLoginModal = false;
    }

    public function openReviewModal($productId)
    {
        $this->selectedProduct = Product::with([
            'productReviews' => function ($query) {
                $query->with('customer')->latest();
            }
        ])->findOrFail($productId);

        $this->productReviews = $this->selectedProduct->productReviews;
        $this->averageRating = $this->selectedProduct->productReviews->avg('rating') ?? 0;
        $this->showReviewModal = true;
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->selectedProduct = null;
        $this->productReviews = [];
        $this->averageRating = 0;

        $this->loadProducts();
        $this->loadBestSellers();
    }

    public function getShopRating()
    {
        return ServiceReview::where('shop_id', $this->shopId)
            ->avg('rating') ?? 0;
    }

    public function getShopRatingCount()
    {
        return ServiceReview::where('shop_id', $this->shopId)->count();
    }

    public function render()
    {
        $shopRating = $this->getShopRating();
        $shopRatingCount = $this->getShopRatingCount();

        return view('livewire.guest.view-products', [
            'shopRating' => $shopRating,
            'shopRatingCount' => $shopRatingCount,
            'bestSellers' => $this->bestSellers,
        ])->layout('components.layouts.guest');
    }
}
