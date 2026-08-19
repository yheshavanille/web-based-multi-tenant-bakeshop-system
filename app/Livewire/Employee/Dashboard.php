<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $employee;
    public $shop;
    public $branch;
    public $role;
    public $orders = [];
    public $products = [];
    public $orderStats = [];
    public $totalProducts = 0;
    public $outOfStockCount = 0;
    public $lowStockCount = 0;
    public $restockSuggestions = [];

    public function mount()
    {
        $this->employee = Auth::user()->employee;
        $this->shop = $this->employee->shop; // ✅ FIXED
        $this->branch = $this->employee->branch;
        $this->role = $this->employee->role;

        if ($this->role === 'order_manager') {
            $this->loadOrderData();
        } elseif ($this->role === 'inventory_manager') {
            $this->loadInventoryData();
        }
    }

    public function loadOrderData()
    {
        $this->orders = Order::where('branch_id', $this->branch->id)
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function loadInventoryData()
    {
        $this->products = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->with([
                'category',
                'branches' => function ($query) {
                    $query->where('branches.id', $this->branch->id);
                },
            ])
            ->get();

        $this->totalProducts = $this->products->count();

        $this->outOfStockCount = $this->products->filter(function ($product) {
            $stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
            return $stock == 0;
        })->count();

        $this->lowStockCount = $this->products->filter(function ($product) {
            $stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
            return $stock > 0 && $stock <= 5;
        })->count();

        // Restock Suggestions - Simplified approach
        $lowStockProducts = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id)
                    ->where('stock', '<=', 5)
                    ->where('stock', '>', 0);
            })
            ->with(['branches' => function ($query) {
                $query->where('branch_id', $this->branch->id);
            }])
            ->get();

        $this->restockSuggestions = $lowStockProducts->filter(function ($product) {
            $orderCount = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) {
                    $q->where('branch_id', $this->branch->id)
                        ->where('created_at', '>=', now()->subDays(7))
                        ->whereIn('status', ['pending', 'preparing', 'ready_for_pickup']);
                })
                ->count();

            $product->orders_last_7_days = $orderCount;

            return $orderCount > 0;
        })->sortByDesc('orders_last_7_days');
    }

    public function render()
    {
        return view('livewire.employee.dashboard')
            ->layout('components.layouts.employee');
    }
}
