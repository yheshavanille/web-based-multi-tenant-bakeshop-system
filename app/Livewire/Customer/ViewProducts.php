<?php

namespace App\Livewire\Customer;

use App\Models\Branch;
use App\Models\Cart;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
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

    public function mount($shopId, $branch = null)
    {
        $this->shopId = $shopId;
        $this->shop = Shop::with('user')->findOrFail($shopId);

        // Get all active branches for this shop
        $this->branches = Branch::where('shop_id', $shopId)
            ->where('is_active', true)
            ->get();

        // Set selected branch
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

        $this->products = $branch->products()
            ->with('category')
            ->wherePivot('stock', '>', 0)
            ->when($this->selectedCategory !== 'all', function ($query) {
                $query->where('products.category_id', $this->selectedCategory);
            })
            ->get();
    }

    // ✅ Best Sellers - Load top 3 products for the selected branch
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

    public function updatedSelectedCategory()
    {
        $this->loadProducts();
    }

    public function getStock($productId)
    {
        $branch = Branch::find($this->selectedBranchId);
        if (!$branch) return 0;

        $pivot = $branch->products()->where('product_id', $productId)->first();
        return $pivot ? $pivot->pivot->stock : 0;
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$this->selectedBranchId) {
            session()->flash('error', 'Please select a branch first.');
            return;
        }

        $branch = Branch::find($this->selectedBranchId);
        $pivot = $branch->products()->where('product_id', $productId)->first();

        if (!$pivot || $pivot->pivot->stock <= 0) {
            session()->flash('error', 'This product is out of stock at the selected branch.');
            return;
        }

        $availableStock = $pivot->pivot->stock;

        // Check if product already in cart for this branch
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('branch_id', $this->selectedBranchId)
            ->first();

        $currentQuantity = $cart ? $cart->quantity : 0;

        if ($currentQuantity >= $availableStock) {
            session()->flash('error', 'You already have the maximum available stock for this product!');
            return;
        }

        if ($cart) {
            $cart->increment('quantity');
            $message = $product->name . ' quantity updated! 🛒';
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'branch_id' => $this->selectedBranchId,
                'quantity' => 1,
            ]);
            $message = $product->name . ' added to cart! 🛒';
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', message: $message);
    }

    public function render()
    {
        return view('livewire.customer.view-products')
            ->layout('components.layouts.customer');
    }
}
