<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BranchProduct;
use App\Notifications\LowStockNotification;
use App\Notifications\OutOfStockNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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

        if (!$this->employee || !$this->employee->shop || $this->employee->shop->trashed()) {
            Auth::logout();
            session()->flash('error', 'Your shop is no longer active.');
            return redirect()->route('livewire.auth.login');
        }

        $this->shop = $this->employee->shop;
        $this->branch = $this->employee->branch;
        $this->role = $this->employee->role;

        if ($this->role === 'order_manager') {
            $this->loadOrderData();
        } elseif ($this->role === 'inventory_manager') {
            $this->loadInventoryData();
            $this->checkAndSendStockNotifications();
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
        // ✅ FIX: Load products with their pivot stock for this branch
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
            ->get()
            ->map(function ($product) {
                // ✅ Add stock as a property from the pivot table
                $product->stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
                return $product;
            });

        $this->totalProducts = $this->products->count();

        $this->outOfStockCount = $this->products->filter(function ($product) {
            return $product->stock == 0;
        })->count();

        $this->lowStockCount = $this->products->filter(function ($product) {
            return $product->stock > 0 && $product->stock <= 5;
        })->count();

        // Restock Suggestions
        $lowStockProducts = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id)
                    ->where('stock', '<=', 5)
                    ->where('stock', '>', 0);
            })
            ->with(['branches' => function ($query) {
                $query->where('branch_id', $this->branch->id);
            }])
            ->get()
            ->map(function ($product) {
                $product->stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
                return $product;
            });

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

    // ✅ Check stock and send notifications (ONLY for stocks <= 5)
    public function checkAndSendStockNotifications()
    {
        // Get all products with their stock for this branch
        $products = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->with(['branches' => function ($query) {
                $query->where('branches.id', $this->branch->id);
            }])
            ->get()
            ->map(function ($product) {
                $product->stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
                return $product;
            });

        $inventoryManager = $this->employee;

        foreach ($products as $product) {
            $stock = $product->stock;

            // ✅ SKIP if stock is greater than 5 (not low stock)
            if ($stock > 5) {
                continue;
            }

            // Check if notification already exists for this product (within last 24 hours)
            $existingNotification = $inventoryManager->user->notifications()
                ->where('data->product_id', $product->id)
                ->where('data->type', 'low_stock')
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if ($existingNotification) {
                continue; // Skip if already notified recently
            }

            if ($stock <= 0) {
                // Out of Stock
                Notification::send(
                    $inventoryManager->user,
                    new OutOfStockNotification($product, $this->branch)
                );
            } elseif ($stock <= 5) {
                // Low Stock (1-5)
                Notification::send(
                    $inventoryManager->user,
                    new LowStockNotification($product, $this->branch, $stock)
                );
            }
        }
    }

    public function render()
    {
        return view('livewire.employee.dashboard')
            ->layout('components.layouts.employee');
    }
}
