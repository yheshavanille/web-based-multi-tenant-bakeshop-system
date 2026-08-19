<?php

namespace App\Livewire\Owner;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ServiceReview;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $branches = [];
    public $totalProducts = 0;
    public $employeesCount = 0;
    public $stockHistories = [];
    public $lowStockItems = [];
    public $totalSales = 0;
    public $totalOrders = 0;
    public $branchPerformance = [];
    public $bestSellers = [];
    public $shopRating = 0;
    public $shopRatingCount = 0;
    public $recentReviews = [];

    // Employee Modal properties
    public $showAllEmployeesModal = false;
    public $allEmployees = [];
    public $employeeBranchFilter = 'all';
    public $employeeRoleFilter = 'all';
    public $employeeSearch = '';

    public function mount()
    {
        $shop = Auth::user()->shop;

        $this->branches = Branch::where('shop_id', $shop->id)
            ->withCount('products')
            ->get();

        $this->totalProducts = Product::where('shop_id', $shop->id)->count();
        $this->employeesCount = Employee::where('shop_id', $shop->id)->count();

        $this->totalSales = Order::where('shop_id', $shop->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        $this->totalOrders = Order::where('shop_id', $shop->id)
            ->where('status', 'completed')
            ->count();

        // Branch performance
        foreach ($this->branches as $branch) {
            $this->branchPerformance[$branch->id] = [
                'name' => $branch->name,
                'sales' => Order::where('branch_id', $branch->id)
                    ->where('status', 'completed')
                    ->sum('total_amount'),
                'orders' => Order::where('branch_id', $branch->id)
                    ->where('status', 'completed')
                    ->count(),
                'rating' => ServiceReview::where('branch_id', $branch->id)->avg('rating') ?? 0,
                'rating_count' => ServiceReview::where('branch_id', $branch->id)->count(),
            ];
        }

        // Best selling products
        $this->bestSellers = OrderItem::whereHas('order', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id)
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
            ->limit(5)
            ->get();

        // Shop overall rating
        $this->shopRating = ServiceReview::where('shop_id', $shop->id)->avg('rating') ?? 0;
        $this->shopRatingCount = ServiceReview::where('shop_id', $shop->id)->count();

        // Recent reviews
        $this->recentReviews = ServiceReview::where('shop_id', $shop->id)
            ->with(['customer', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $this->stockHistories = StockHistory::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
            ->with(['product', 'user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $this->lowStockItems = Product::where('shop_id', $shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('stock', '<=', 5)
                    ->where('stock', '>', 0);
            })
            ->with('branches')
            ->get();
    }

    // Employee Modal methods
    public function openEmployeesModal()
    {
        $this->loadAllEmployees();
        $this->showAllEmployeesModal = true;
    }

    public function closeEmployeesModal()
    {
        $this->showAllEmployeesModal = false;
        $this->allEmployees = [];
        $this->employeeBranchFilter = 'all';
        $this->employeeRoleFilter = 'all';
        $this->employeeSearch = '';
    }

    public function loadAllEmployees()
    {
        $shop = Auth::user()->shop;
        $query = Employee::with(['user', 'branch'])
            ->where('shop_id', $shop->id);

        if ($this->employeeBranchFilter !== 'all') {
            $query->where('branch_id', $this->employeeBranchFilter);
        }

        if ($this->employeeRoleFilter !== 'all') {
            $query->where('role', $this->employeeRoleFilter);
        }

        if (!empty($this->employeeSearch)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->employeeSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->employeeSearch . '%');
            });
        }

        $this->allEmployees = $query->get();
    }

    public function updatedEmployeeBranchFilter()
    {
        $this->loadAllEmployees();
    }

    public function updatedEmployeeRoleFilter()
    {
        $this->loadAllEmployees();
    }

    public function updatedEmployeeSearch()
    {
        $this->loadAllEmployees();
    }

    public function render()
    {
        return view('livewire.owner.dashboard')
            ->layout('components.layouts.owner');
    }
}
