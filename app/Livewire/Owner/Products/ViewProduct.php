<?php

namespace App\Livewire\Owner\Products;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ViewProduct extends Component
{
    public string $selectedCategory = 'all';
    public $selectedBranchId = null;
    public $showDeleted = false;
    public Collection $categories;

    // Modal properties
    public $showProductModal = false;
    public $selectedProduct = null;
    public $productAnalytics = [];

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

    public function viewProductDetails($productId)
    {
        $this->selectedProduct = Product::with('branches', 'category')
            ->findOrFail($productId);

        // Calculate analytics
        $orderItems = OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed');
            })
            ->get();

        $this->productAnalytics = [
            'total_sold' => $orderItems->sum('quantity'),
            'total_orders' => $orderItems->groupBy('order_id')->count(),
            'total_revenue' => $orderItems->sum(function ($item) {
                return $item->quantity * $item->price;
            }),
        ];

        $this->showProductModal = true;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
        $this->productAnalytics = [];
        $this->dispatch('product-modal-closed');
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
        ])->layout('components.layouts.owner');
    }
}
