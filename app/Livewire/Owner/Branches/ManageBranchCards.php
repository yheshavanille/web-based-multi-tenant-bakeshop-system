<?php

namespace App\Livewire\Owner\Branches;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceReview;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageBranchCards extends Component
{
    public $branches = [];
    public $search = '';

    // Modal properties
    public $showDetailsModal = false;
    public $selectedBranch = null;
    public $branchStats = [];

    public function mount()
    {
        $this->loadBranches();
    }

    public function updatedSearch()
    {
        $this->loadBranches();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadBranches();
    }

    public function loadBranches()
    {
        $shop = Auth::user()->shop;
        $query = Branch::where('shop_id', $shop->id)
            ->withCount(['products', 'employees']);

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm);
            });
        }

        $this->branches = $query->get();
    }

    public function viewBranchDetails($branchId)
    {
        $this->selectedBranch = Branch::with(['products', 'employees', 'shop'])
            ->withCount(['products', 'employees'])
            ->findOrFail($branchId);

        // Calculate branch stats
        $this->branchStats = $this->calculateBranchStats($branchId);

        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedBranch = null;
        $this->branchStats = [];
    }

    private function calculateBranchStats($branchId)
    {
        $stats = [];

        // Get all orders for this branch
        $orders = Order::where('branch_id', $branchId)->get();

        // Total orders
        $stats['total_orders'] = $orders->count();

        // Completed orders
        $stats['completed_orders'] = $orders->where('status', 'completed')->count();

        // Pending orders
        $stats['pending_orders'] = $orders->where('status', 'pending')->count();

        // Preparing orders
        $stats['preparing_orders'] = $orders->where('status', 'preparing')->count();

        // Ready for pickup orders
        $stats['ready_orders'] = $orders->where('status', 'ready_for_pickup')->count();

        // Cancelled orders
        $stats['cancelled_orders'] = $orders->where('status', 'cancelled')->count();

        // Total revenue (from completed orders) - EXCLUDE CANCELLED ITEMS
        $completedOrders = $orders->where('status', 'completed');
        $totalRevenue = 0;
        foreach ($completedOrders as $order) {
            $adjustedTotal = $order->items
                ->where('status', '!=', 'cancelled')
                ->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
            $totalRevenue += $adjustedTotal;
        }
        $stats['total_revenue'] = $totalRevenue;

        // Average order value
        $stats['avg_order_value'] = $stats['completed_orders'] > 0
            ? $stats['total_revenue'] / $stats['completed_orders']
            : 0;

        // Total products in branch
        $stats['total_products'] = $this->selectedBranch->products->count();

        // Total employees
        $stats['total_employees'] = $this->selectedBranch->employees->count();

        // Total order items sold
        $stats['total_items_sold'] = OrderItem::whereHas('order', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId)
                ->where('status', 'completed');
        })->sum('quantity');

        // Average rating
        $stats['avg_rating'] = ServiceReview::where('branch_id', $branchId)->avg('rating') ?? 0;
        $stats['rating_count'] = ServiceReview::where('branch_id', $branchId)->count();

        // Employee role breakdown
        $stats['order_managers'] = Employee::where('branch_id', $branchId)
            ->where('role', 'order_manager')
            ->where('is_active', true)
            ->count();

        $stats['inventory_managers'] = Employee::where('branch_id', $branchId)
            ->where('role', 'inventory_manager')
            ->where('is_active', true)
            ->count();

        // Recent orders (last 5) - WITH ADJUSTED TOTALS
        $stats['recent_orders'] = Order::where('branch_id', $branchId)
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                // ✅ Calculate adjusted total (exclude cancelled items)
                $adjustedTotal = $order->items
                    ->where('status', '!=', 'cancelled')
                    ->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });

                // ✅ Calculate adjusted tax and grand total
                $adjustedTax = round($adjustedTotal * 0.12, 2);
                $adjustedGrandTotal = $adjustedTotal + $adjustedTax;

                $order->display_total = $adjustedGrandTotal;
                $order->adjusted_total = $adjustedTotal;

                return $order;
            });

        return $stats;
    }

    public function viewBranchProducts($branchId)
    {
        return redirect()->route('livewire.owner.products.view-product', ['branch' => $branchId]);
    }

    public function render()
    {
        return view('livewire.owner.branches.manage-branch-cards')
            ->layout('components.layouts.owner');
    }
}
