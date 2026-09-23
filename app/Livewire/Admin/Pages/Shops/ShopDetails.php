<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductEditHistory;
use App\Models\Shop;
use App\Models\StockHistory;
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

    // ✅ Best Sellers
    public $bestSellers = [];

    // ✅ Recent Employee Activities
    public $recentEmployeeActivities = [];

    // ✅ Recent Stock Updates
    public $stockHistories = [];
    public $showStockHistoryModal = false;
    public $allStockHistories = [];

    // ✅ Recent Product Updates
    public $productEditHistories = [];
    public $showProductHistoryModal = false;
    public $allProductHistories = [];

    // ✅ Product Details Modal
    public $showProductModal = false;
    public $selectedProduct = null;
    public $productAnalytics = [];

    // Recent Orders properties
    public $recentOrders = [];
    public $showAllOrdersModal = false;
    public $allOrders = [];
    public $orderSearch = '';
    public $showOrderDetailsModal = false;
    public $selectedOrder = null;

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

        // Total Sales — item-level, net revenue (excludes VAT & cancelled)
        $this->totalSales = OrderItem::whereHas('order', function ($query) use ($shopId, $branchId) {
            $query->where('shop_id', $shopId)
                ->whereIn('status', ['completed', 'partially_completed']);
            if ($branchId !== 'all') {
                $query->where('branch_id', $branchId);
            }
        })
            ->where('status', 'completed')
            ->sum(DB::raw('quantity * price'));

        // Total Orders — distinct orders with completed items
        $this->totalOrders = OrderItem::whereHas('order', function ($query) use ($shopId, $branchId) {
            $query->where('shop_id', $shopId)
                ->whereIn('status', ['completed', 'partially_completed']);
            if ($branchId !== 'all') {
                $query->where('branch_id', $branchId);
            }
        })
            ->where('status', 'completed')
            ->distinct('order_id')
            ->count('order_id');

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

        // ✅ Best Sellers (respects branch filter)
        $this->loadBestSellers();

        // Load Recent Orders (last 5)
        $this->loadRecentOrders();

        // Load recent employee activities
        $this->loadRecentEmployeeActivities();

        // Load recent stock updates
        $this->loadRecentStockHistories();

        // Load recent product updates
        $this->loadRecentProductHistories();
    }

    // ✅ NEW: Best Selling Products for this shop
    public function loadBestSellers()
    {
        $shopId = $this->shop->id;
        $branchId = $this->selectedBranch;

        $query = OrderItem::whereHas('order', function ($q) use ($shopId, $branchId) {
            $q->where('shop_id', $shopId)
                ->whereIn('status', ['completed', 'partially_completed']);
            if ($branchId !== 'all') {
                $q->where('branch_id', $branchId);
            }
        })
            ->where('status', 'completed')
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5);

        $this->bestSellers = $query->get();
    }

    // ✅ Recent Employee Activities for this shop
    public function loadRecentEmployeeActivities()
    {
        $this->recentEmployeeActivities = EmployeeActivity::where('shop_id', $this->shop->id)
            ->with(['employee.user', 'employee.branch'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    // ✅ Recent Stock Updates for this shop
    public function loadRecentStockHistories()
    {
        $this->stockHistories = StockHistory::whereHas('product', function ($query) {
            $query->where('shop_id', $this->shop->id);
        })
            ->with(['product', 'user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    // ✅ Recent Product Updates for this shop
    public function loadRecentProductHistories()
    {
        $this->productEditHistories = ProductEditHistory::whereHas('product', function ($query) {
            $query->where('shop_id', $this->shop->id);
        })
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    // ✅ View all stock histories
    public function viewAllStockHistory()
    {
        $this->allStockHistories = StockHistory::whereHas('product', function ($query) {
            $query->where('shop_id', $this->shop->id);
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

    // ✅ View all product edit histories
    public function viewAllProductHistory()
    {
        $this->allProductHistories = ProductEditHistory::whereHas('product', function ($query) {
            $query->where('shop_id', $this->shop->id);
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

    // ✅ Product Details Modal
    // ✅ UPDATED: only load visible/kept reviews
    public function viewProductDetails($productId)
    {
        $this->selectedProduct = Product::with([
            'branches' => function ($query) {
                $query->withPivot('stock');
            },
            'category',
            'productReviews' => function ($query) {
                $query->whereIn('moderation_status', ['visible', 'kept'])
                    ->with('customer')
                    ->latest();
            }
        ])->findOrFail($productId);

        $branchIds = $this->selectedProduct->branches->pluck('id')->toArray();

        if (!empty($branchIds)) {
            $orderItems = OrderItem::where('product_id', $productId)
                ->whereHas('order', function ($q) use ($branchIds) {
                    $q->where('status', 'completed')
                        ->whereIn('branch_id', $branchIds);
                })
                ->get();
        } else {
            $orderItems = OrderItem::where('product_id', $productId)
                ->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                })
                ->get();
        }

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
    }

    // ✅ Show ALL orders (not just completed)
    public function loadRecentOrders()
    {
        $shopId = $this->shop->id;
        $branchId = $this->selectedBranch;

        $query = Order::with(['customer', 'branch', 'items'])
            ->where('shop_id', $shopId)
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->orderBy('updated_at', 'desc');

        if ($branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $this->recentOrders = $query->limit(5)->get()->map(function ($order) {
            return $this->enrichOrder($order);
        });
    }

    private function enrichOrder($order)
    {
        $itemCount = $order->items->count();
        $cancelledCount = $order->items->where('status', 'cancelled')->count();
        $completedCount = $order->items->where('status', 'completed')->count();
        $pendingCount = $order->items->where('status', 'pending')->count();
        $preparingCount = $order->items->where('status', 'preparing')->count();
        $readyCount = $order->items->where('status', 'ready_for_pickup')->count();
        $noShowCount = $order->items->where('status', 'no_show')->count();

        $isFullyCancelled = $order->status === 'cancelled' || $cancelledCount === $itemCount;
        $isPartiallyCompleted = $order->status === 'partially_completed' || ($completedCount > 0 && $cancelledCount > 0 && $pendingCount === 0);

        $statusParts = [];
        if ($completedCount > 0) $statusParts[] = $completedCount . ' completed';
        if ($cancelledCount > 0) $statusParts[] = $cancelledCount . ' cancelled';
        if ($pendingCount > 0) $statusParts[] = $pendingCount . ' pending';
        if ($preparingCount > 0) $statusParts[] = $preparingCount . ' preparing';
        if ($readyCount > 0) $statusParts[] = $readyCount . ' ready';
        if ($noShowCount > 0) $statusParts[] = $noShowCount . ' no show';
        if ($isPartiallyCompleted) $statusParts[] = 'partially completed';

        $order->status_summary = !empty($statusParts)
            ? implode(', ', $statusParts)
            : ucfirst(str_replace('_', ' ', $order->status));

        $order->item_count = $itemCount;
        $order->cancelled_count = $cancelledCount;
        $order->completed_count = $completedCount;
        $order->pending_count = $pendingCount;

        $adjustedTotal = $order->items
            ->where('status', '!=', 'cancelled')
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });

        if ($isFullyCancelled) {
            $adjustedTotal = 0;
        }

        $adjustedTax = round($adjustedTotal * 0.12, 2);
        $order->display_total = $adjustedTotal + $adjustedTax;
        $order->adjusted_total = $adjustedTotal;
        $order->adjusted_tax = $adjustedTax;

        return $order;
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

        $query = Order::with(['customer', 'branch', 'items'])
            ->where('shop_id', $shopId)
            ->where(function ($q) {
                $q->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->orderBy('updated_at', 'desc');

        if ($branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $this->allOrders = $query->get()->map(function ($order) {
            return $this->enrichOrder($order);
        });
        $this->showAllOrdersModal = true;
    }

    public function closeAllOrdersModal()
    {
        $this->showAllOrdersModal = false;
        $this->allOrders = [];
        $this->orderSearch = '';
        $this->dispatch('all-orders-modal-closed');
    }

    public function viewOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['customer', 'branch', 'items.product', 'shop', 'serviceReview'])
            ->findOrFail($orderId);

        $this->selectedOrder->adjusted_total = $this->selectedOrder->items
            ->where('status', '!=', 'cancelled')
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });

        $this->showOrderDetailsModal = true;
    }

    public function closeOrderDetailsModal()
    {
        $this->showOrderDetailsModal = false;
        $this->selectedOrder = null;
    }

    // ✅ Calculate the 4-section breakdown with product lists
    public function getBreakdown()
    {
        if (!$this->selectedOrder) {
            return null;
        }

        $items = $this->selectedOrder->items;

        $completedItems = $items->where('status', 'completed');
        $completedSubtotal = $completedItems->sum(fn($item) => $item->price * $item->quantity);
        $completedVat = round($completedSubtotal * 0.12, 2);

        $notChargedItems = $items->whereIn('status', ['no_show', 'cancelled']);
        $notChargedSubtotal = $notChargedItems->sum(fn($item) => $item->price * $item->quantity);
        $notChargedVat = round($notChargedSubtotal * 0.12, 2);

        $outstandingItems = $items->whereIn('status', ['pending', 'preparing', 'ready_for_pickup']);
        $outstandingSubtotal = $outstandingItems->sum(fn($item) => $item->price * $item->quantity);
        $outstandingVat = round($outstandingSubtotal * 0.12, 2);

        $originalSubtotal = $items->sum(fn($item) => $item->price * $item->quantity);
        $originalVat = round($originalSubtotal * 0.12, 2);

        $mapItems = function ($collection) {
            return $collection->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'Product Unavailable',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                    'status' => $item->status,
                ];
            })->values()->toArray();
        };

        return [
            'original_subtotal' => $originalSubtotal,
            'original_vat' => $originalVat,
            'original_total' => $originalSubtotal + $originalVat,
            'original_items' => $mapItems($items),

            'charged_subtotal' => $completedSubtotal,
            'charged_vat' => $completedVat,
            'amount_charged' => $completedSubtotal + $completedVat,
            'charged_items' => $mapItems($completedItems),

            'not_charged_subtotal' => $notChargedSubtotal,
            'not_charged_vat' => $notChargedVat,
            'amount_not_charged' => $notChargedSubtotal + $notChargedVat,
            'not_charged_items' => $mapItems($notChargedItems),

            'outstanding_subtotal' => $outstandingSubtotal,
            'outstanding_vat' => $outstandingVat,
            'amount_outstanding' => $outstandingSubtotal + $outstandingVat,
            'outstanding_items' => $mapItems($outstandingItems),
        ];
    }

    public function render()
    {
        $this->loadAnalytics();

        $query = Product::with('category', 'branches')
            ->where('shop_id', $this->shop->id)
            ->withCount(['productReviews as product_reviews_count' => function ($q) {
                $q->whereIn('moderation_status', ['visible', 'kept']);
            }])
            ->withSum(['orderItems as total_sold' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                });
            }], 'quantity');

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->selectedBranch !== 'all') {
            $query->whereHas('branches', function ($q) {
                $q->where('branch_id', $this->selectedBranch);
            });
        }

        $products = $query->get();

        foreach ($products as $product) {
            // ✅ Match modal logic — filter by branch IDs where this product exists
            $branchIds = $product->branches->pluck('id')->toArray();

            if (!empty($branchIds)) {
                $orderItems = OrderItem::where('product_id', $product->id)
                    ->whereHas('order', function ($q) use ($branchIds) {
                        $q->where('status', 'completed')
                            ->whereIn('branch_id', $branchIds);
                    })
                    ->get();
            } else {
                $orderItems = OrderItem::where('product_id', $product->id)
                    ->whereHas('order', function ($q) {
                        $q->where('status', 'completed');
                    })
                    ->get();
            }

            $product->total_sold = $orderItems->sum('quantity');
            $product->total_revenue = $orderItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            // ✅ Stock calculation (respects branch filter)
            if ($this->selectedBranch !== 'all') {
                $branch = $product->branches->firstWhere('id', $this->selectedBranch);
                $product->current_stock = $branch ? $branch->pivot->stock : 0;
            } else {
                $product->current_stock = $product->branches->sum('pivot.stock');
            }
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
