<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ShopDetails extends Component
{
    public $shop;
    public $categories;
    public $branches;
    public $selectedCategory = 'all';
    public $selectedBranch = 'all';

    // Analytics properties
    public $totalSales = 0;
    public $totalOrders = 0;
    public $totalProducts = 0;
    public $totalEmployees = 0;

    // Recent Orders properties
    public $recentOrders = [];
    public $showAllOrdersModal = false;
    public $allOrders = [];
    public $orderSearch = '';

    public function mount($shopId)
    {
        $this->shop = Shop::with(['user'])->findOrFail($shopId);

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $this->shop->id)
            ->get();

        $this->branches = Branch::where('shop_id', $this->shop->id)
            ->where('is_active', true)
            ->get();
    }

    public function loadAnalytics()
    {
        $shopId = $this->shop->id;
        $branchId = $this->selectedBranch;

        // Total Sales
        $salesQuery = Order::where('shop_id', $shopId)->where('status', 'completed');
        if ($branchId !== 'all') {
            $salesQuery->where('branch_id', $branchId);
        }
        $this->totalSales = $salesQuery->sum('total_amount');

        // Total Orders
        $ordersQuery = Order::where('shop_id', $shopId)->where('status', 'completed');
        if ($branchId !== 'all') {
            $ordersQuery->where('branch_id', $branchId);
        }
        $this->totalOrders = $ordersQuery->count();

        // Total Products
        $productsQuery = Product::where('shop_id', $shopId);
        if ($branchId !== 'all') {
            $productsQuery->whereHas('branches', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        $this->totalProducts = $productsQuery->count();

        // Total Employees
        $employeesQuery = Employee::where('shop_id', $shopId);
        if ($branchId !== 'all') {
            $employeesQuery->where('branch_id', $branchId);
        }
        $this->totalEmployees = $employeesQuery->count();

        // Load Recent Orders (last 5)
        $this->loadRecentOrders();
    }

    public function loadRecentOrders()
    {
        $shopId = $this->shop->id;
        $branchId = $this->selectedBranch;

        $query = Order::with(['customer', 'branch'])
            ->where('shop_id', $shopId)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc');

        if ($branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $this->recentOrders = $query->limit(5)->get();
    }

    public function updatedSelectedBranch()
    {
        $this->loadAnalytics();
        $this->loadRecentOrders();
    }

    public function openAllOrdersModal()
    {
        $shopId = $this->shop->id;
        $branchId = $this->selectedBranch;

        $query = Order::with(['customer', 'branch'])
            ->where('shop_id', $shopId)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc');

        if ($branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $this->allOrders = $query->get();
        $this->showAllOrdersModal = true;
    }

    public function closeAllOrdersModal()
    {
        $this->showAllOrdersModal = false;
        $this->allOrders = [];
        $this->orderSearch = '';
        $this->dispatch('all-orders-modal-closed');
    }

    public function render()
    {
        // Load analytics
        $this->loadAnalytics();

        $query = Product::with('category', 'branches')
            ->where('shop_id', $this->shop->id)
            ->withSum(['orderItems as total_sold' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                });
            }], 'quantity');

        // Filter by category
        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        // Filter by branch
        if ($this->selectedBranch !== 'all') {
            $query->whereHas('branches', function ($q) {
                $q->where('branch_id', $this->selectedBranch);
            });
        }

        $products = $query->get();

        // Calculate total revenue correctly
        foreach ($products as $product) {
            $product->total_revenue = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                })
                ->get()
                ->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
        }

        $employees = Employee::with('user')
            ->where('shop_id', $this->shop->id)
            ->when($this->selectedBranch !== 'all', function ($q) {
                $q->where('branch_id', $this->selectedBranch);
            })
            ->get();

        return view('livewire.admin.pages.shops.shop-details', [
            'products' => $products,
            'categories' => $this->categories,
            'branches' => $this->branches,
            'employees' => $employees,
        ])->layout('components.layouts.admin');
    }
}
