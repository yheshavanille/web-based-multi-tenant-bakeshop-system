<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerRegistration;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalShops;
    public $totalProducts;
    public $pendingSellers;
    public $recentApplications;

    // ✅ Best Selling Shops
    public $topShops = [];
    public $allShopsRanked = [];

    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalShops = Shop::count();
        $this->totalProducts = Product::count();
        $this->pendingSellers = SellerRegistration::where('status', 'pending')->count();

        $this->recentApplications = SellerRegistration::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $this->loadBestSellingShops();
    }

    public function loadBestSellingShops()
    {
        $shops = Shop::with('user')
            ->withCount('orders')
            ->get();

        $shopPerformance = [];

        foreach ($shops as $shop) {

            // ✅ MATCH ShopDetails.php — item-level, net of VAT, includes partials
            $totalSales = OrderItem::whereHas('order', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id)
                    ->whereIn('status', ['completed', 'partially_completed']);
            })
                ->where('status', 'completed')
                ->sum(DB::raw('quantity * price'));

            $totalOrders = OrderItem::whereHas('order', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id)
                    ->whereIn('status', ['completed', 'partially_completed']);
            })
                ->where('status', 'completed')
                ->distinct('order_id')
                ->count('order_id');

            // Average rating
            $avgRating = DB::table('service_reviews')
                ->where('shop_id', $shop->id)
                ->avg('rating') ?? 0;

            // ✅ MATCH ShopDetails.php — top product uses same filter as sales
            $topProduct = OrderItem::whereHas('order', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id)
                    ->whereIn('status', ['completed', 'partially_completed']);
            })
                ->where('status', 'completed')
                ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->with('product')
                ->first();

            $shopPerformance[] = [
                'shop' => $shop,
                'owner' => $shop->user,
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'avg_rating' => round($avgRating, 1),
                'top_product' => $topProduct?->product?->name ?? 'N/A',
                'top_product_sold' => $topProduct?->total_sold ?? 0,
            ];
        }

        // Sort by total sales (descending)
        usort($shopPerformance, function ($a, $b) {
            return $b['total_sales'] <=> $a['total_sales'];
        });

        // Top 3 for banner
        $this->topShops = array_slice($shopPerformance, 0, 3);

        // All for table (with rank)
        $this->allShopsRanked = $shopPerformance;
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('components.layouts.admin');
    }
}
