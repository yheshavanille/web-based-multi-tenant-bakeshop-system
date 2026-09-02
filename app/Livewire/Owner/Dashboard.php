<?php

namespace App\Livewire\Owner;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductEditHistory;
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
    public $productEditHistories = [];
    public $lowStockItems = [];
    public $totalSales = 0;
    public $totalOrders = 0;
    public $branchPerformance = [];
    public $bestSellers = [];
    public $shopRating = 0;
    public $shopRatingCount = 0;
    public $recentReviews = [];
    public $recentOrders = [];

    // Order Details Modal
    public $showOrderModal = false;
    public $selectedOrder = null;

    // Product Edit Details Modal
    public $showProductEditModal = false;
    public $selectedProductEdit = null;

    // ✅ Stock History Modal
    public $showStockHistoryModal = false;
    public $allStockHistories = [];

    // ✅ Product Edit History Modal
    public $showProductHistoryModal = false;
    public $allProductHistories = [];

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

        // Stock histories
        $this->stockHistories = StockHistory::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
            ->with(['product', 'user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Product Edit Histories
        $this->productEditHistories = ProductEditHistory::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
            ->with(['product', 'user'])
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

        $this->loadRecentOrders();
    }

    public function loadRecentOrders()
    {
        $shop = Auth::user()->shop;

        $this->recentOrders = Order::where('shop_id', $shop->id)
            ->with(['customer', 'branch', 'items', 'serviceReview'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                $itemCount = $order->items->count();
                $cancelledCount = $order->items->where('status', 'cancelled')->count();
                $completedCount = $order->items->where('status', 'completed')->count();
                $pendingCount = $order->items->where('status', 'pending')->count();
                $preparingCount = $order->items->where('status', 'preparing')->count();
                $readyCount = $order->items->where('status', 'ready_for_pickup')->count();

                // ✅ Calculate adjusted total (exclude cancelled items)
                $adjustedTotal = $order->items
                    ->where('status', '!=', 'cancelled')
                    ->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });

                // ✅ Calculate adjusted tax and grand total
                $adjustedTax = round($adjustedTotal * 0.12, 2);
                $adjustedGrandTotal = $adjustedTotal + $adjustedTax;

                // ✅ Build status summary
                $statusParts = [];
                if ($completedCount > 0) $statusParts[] = $completedCount . ' completed';
                if ($cancelledCount > 0) $statusParts[] = $cancelledCount . ' cancelled';
                if ($pendingCount > 0) $statusParts[] = $pendingCount . ' pending';
                if ($preparingCount > 0) $statusParts[] = $preparingCount . ' preparing';
                if ($readyCount > 0) $statusParts[] = $readyCount . ' ready';

                $order->status_summary = !empty($statusParts)
                    ? implode(', ', $statusParts)
                    : ucfirst(str_replace('_', ' ', $order->status));

                $order->item_count = $itemCount;
                $order->cancelled_count = $cancelledCount;
                $order->completed_count = $completedCount;
                $order->pending_count = $pendingCount;

                // ✅ OVERRIDE the total amount with adjusted total
                $order->display_total = $adjustedGrandTotal;
                $order->adjusted_total = $adjustedTotal;
                $order->adjusted_tax = $adjustedTax;

                return $order;
            });
    }

    public function viewOrderDetails($orderId)
    {
        // ✅ Load order with ALL relationships including product reviews
        $this->selectedOrder = Order::with([
            'customer',
            'branch',
            'items.product',
            'shop',
            'serviceReview',
            // ✅ Load product reviews for this order's products
            'productReviews' => function ($query) {
                $query->with('product')->orderBy('created_at', 'desc');
            }
        ])->findOrFail($orderId);

        // ✅ Calculate adjusted total (exclude cancelled items)
        $adjustedTotal = $this->selectedOrder->items
            ->where('status', '!=', 'cancelled')
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });

        // ✅ Set adjusted values for the modal
        $this->selectedOrder->adjusted_total = $adjustedTotal;
        $this->selectedOrder->subtotal = $adjustedTotal;
        $this->selectedOrder->tax_amount = round($adjustedTotal * 0.12, 2);
        $this->selectedOrder->total_amount = $adjustedTotal + $this->selectedOrder->tax_amount;

        $this->showOrderModal = true;
    }

    public function closeOrderModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
    }

    // ✅ Product Edit Details Methods
    public function viewProductEditDetails($historyId)
    {
        $this->selectedProductEdit = ProductEditHistory::with(['product', 'user'])
            ->findOrFail($historyId);
        $this->showProductEditModal = true;
    }

    public function closeProductEditModal()
    {
        $this->showProductEditModal = false;
        $this->selectedProductEdit = null;
    }

    // ✅ View All Stock History
    public function viewAllStockHistory()
    {
        $shop = Auth::user()->shop;
        $this->allStockHistories = StockHistory::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
            ->with(['product', 'user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showStockHistoryModal = true;
    }

    public function closeStockHistoryModal()
    {
        $this->showStockHistoryModal = false;
        $this->allStockHistories = [];
    }

    // ✅ View All Product History
    public function viewAllProductHistory()
    {
        $shop = Auth::user()->shop;
        $this->allProductHistories = ProductEditHistory::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showProductHistoryModal = true;
    }

    public function closeProductHistoryModal()
    {
        $this->showProductHistoryModal = false;
        $this->allProductHistories = [];
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
