<?php

namespace App\Livewire\Owner;

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SalesReport extends Component
{
    public string $period = 'month'; // today | week | month | year

    // ✅ NEW: selected month for the "This Month" tab (format: YYYY-MM)
    public string $selectedMonth = '';

    // ✅ NEW: list of available months (last 24), each is ['value' => 'YYYY-MM', 'label' => 'Month YYYY']
    public array $availableMonths = [];

    public $startDate;
    public $endDate;

    public float $totalRevenue = 0;
    public int $totalOrders = 0;
    public float $averageOrderValue = 0;
    public array $branchBreakdown = [];
    public array $bestSellers = [];
    public array $dailyTrend = [];
    public string $trendGrouping = 'day';
    public array $chartData = ['labels' => [], 'revenue' => [], 'orders' => []];

    public function mount()
    {
        // ✅ Default: current month
        $this->selectedMonth = Carbon::now()->format('Y-m');

        // ✅ Build the last 24 months list
        $this->buildAvailableMonths();

        // ✅ Read URL params
        if (request()->has('period') && in_array(request()->get('period'), ['today', 'week', 'month', 'year'])) {
            $this->period = request()->get('period');
        }

        if (request()->has('month')) {
            $requested = request()->get('month');
            // Only accept if it's in the availableMonths list
            $validValues = collect($this->availableMonths)->pluck('value')->toArray();
            if (in_array($requested, $validValues)) {
                $this->selectedMonth = $requested;
            }
        }

        $this->computeDateRange();
        $this->loadReport();
    }

    // ✅ NEW: build the list of last 24 months
    private function buildAvailableMonths(): void
    {
        $months = [];
        $cursor = Carbon::now()->startOfMonth();

        for ($i = 0; $i < 24; $i++) {
            $months[] = [
                'value' => $cursor->format('Y-m'),
                'label' => $cursor->format('F Y'), // e.g. "September 2026"
            ];
            $cursor->subMonth();
        }

        $this->availableMonths = $months;
    }

    private function computeDateRange(): void
    {
        $now = Carbon::now();

        switch ($this->period) {
            case 'today':
                $this->startDate = $now->copy()->startOfDay();
                $this->endDate   = $now->copy()->endOfDay();
                break;
            case 'week':
                $this->startDate = $now->copy()->startOfWeek();
                $this->endDate   = $now->copy()->endOfWeek();
                break;
            case 'year':
                $this->startDate = $now->copy()->startOfYear();
                $this->endDate   = $now->copy()->endOfYear();
                break;
            case 'month':
            default:
                // ✅ Use the SELECTED month (not necessarily the current one)
                $selected = Carbon::createFromFormat('Y-m', $this->selectedMonth ?: $now->format('Y-m'));
                $this->startDate = $selected->copy()->startOfMonth();
                $this->endDate   = $selected->copy()->endOfMonth();
                break;
        }

        // Trend grouping: Year → monthly, others → daily
        $this->trendGrouping = $this->period === 'year' ? 'month' : 'day';
    }

    public function setPeriod(string $period): void
    {
        if (!in_array($period, ['today', 'week', 'month', 'year'])) {
            return;
        }

        $this->period = $period;
        $this->computeDateRange();
        $this->loadReport();
    }

    // ✅ NEW: change the selected month (only meaningful in 'month' period)
    public function setMonth(string $month): void
    {
        $validValues = collect($this->availableMonths)->pluck('value')->toArray();

        if (!in_array($month, $validValues)) {
            return;
        }

        $this->selectedMonth = $month;
        $this->period = 'month'; // ensure we're in month mode
        $this->computeDateRange();
        $this->loadReport();
    }

    public function loadReport(): void
    {
        $shop = Auth::user()->shop;
        $shopId = $shop->id;
        $start = $this->startDate;
        $end = $this->endDate;

        $baseQuery = OrderItem::whereHas('order', function ($q) use ($shopId, $start, $end) {
            $q->where('shop_id', $shopId)
                ->whereIn('status', ['completed', 'partially_completed'])
                ->whereBetween('created_at', [$start, $end]);
        })->where('status', 'completed');

        $this->totalRevenue = (float) (clone $baseQuery)->sum(DB::raw('quantity * price'));
        $this->totalOrders  = (int) (clone $baseQuery)->distinct('order_id')->count('order_id');
        $this->averageOrderValue = $this->totalOrders > 0
            ? round($this->totalRevenue / $this->totalOrders, 2)
            : 0;

        $this->branchBreakdown = [];
        $branches = Branch::where('shop_id', $shopId)->orderBy('name')->get();

        foreach ($branches as $branch) {
            $revenue = (float) OrderItem::whereHas('order', function ($q) use ($branch, $start, $end) {
                $q->where('branch_id', $branch->id)
                    ->whereIn('status', ['completed', 'partially_completed'])
                    ->whereBetween('created_at', [$start, $end]);
            })->where('status', 'completed')->sum(DB::raw('quantity * price'));

            $orders = (int) OrderItem::whereHas('order', function ($q) use ($branch, $start, $end) {
                $q->where('branch_id', $branch->id)
                    ->whereIn('status', ['completed', 'partially_completed'])
                    ->whereBetween('created_at', [$start, $end]);
            })->where('status', 'completed')->distinct('order_id')->count('order_id');

            $this->branchBreakdown[] = [
                'id' => $branch->id,
                'name' => $branch->name,
                'revenue' => $revenue,
                'orders' => $orders,
                'avg' => $orders > 0 ? round($revenue / $orders, 2) : 0,
            ];
        }

        $this->bestSellers = OrderItem::whereHas('order', function ($q) use ($shopId, $start, $end) {
            $q->where('shop_id', $shopId)
                ->whereIn('status', ['completed', 'partially_completed'])
                ->whereBetween('created_at', [$start, $end]);
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
            ->limit(5)
            ->get()
            ->toArray();

        $this->loadTrend($shopId, $start, $end);
    }

    private function loadTrend($shopId, $start, $end): void
    {
        $isMonthly = $this->trendGrouping === 'month';

        $dateExpression = $isMonthly
            ? DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bucket")
            : DB::raw("DATE(created_at) as bucket");

        $rows = OrderItem::whereHas('order', function ($q) use ($shopId, $start, $end) {
            $q->where('shop_id', $shopId)
                ->whereIn('status', ['completed', 'partially_completed'])
                ->whereBetween('created_at', [$start, $end]);
        })
            ->where('status', 'completed')
            ->select(
                $dateExpression,
                DB::raw('SUM(quantity * price) as revenue'),
                DB::raw('COUNT(DISTINCT order_id) as orders')
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $this->dailyTrend = $rows->map(function ($row) use ($isMonthly) {
            $label = $isMonthly
                ? Carbon::createFromFormat('Y-m', $row->bucket)->format('F Y')
                : Carbon::parse($row->bucket)->format('M d, Y (D)');

            return [
                'bucket' => $row->bucket,
                'label' => $label,
                'orders' => (int) $row->orders,
                'revenue' => (float) $row->revenue,
            ];
        })->toArray();

        //  NEW: also expose a compact array for the chart
        $this->chartData = [
            'labels' => array_column($this->dailyTrend, 'label'),
            'revenue' => array_column($this->dailyTrend, 'revenue'),
            'orders' => array_column($this->dailyTrend, 'orders'),
        ];
    }
    //  UPDATED: dynamic label based on period + month
    public function getPeriodLabelProperty(): string
    {
        return match ($this->period) {
            'today' => 'Today',
            'week'  => 'This Week',
            'year'  => 'This Year',
            default => Carbon::createFromFormat('Y-m', $this->selectedMonth ?: Carbon::now()->format('Y-m'))->format('F Y'),
        };
    }

    public function getDateRangeLabelProperty(): string
    {
        if (!$this->startDate || !$this->endDate) return '';
        return $this->startDate->format('M d, Y') . ' — ' . $this->endDate->format('M d, Y');
    }

    public function render()
    {
        return view('livewire.owner.sales-report', [
            'periodLabel' => $this->getPeriodLabelProperty(),
            'dateRangeLabel' => $this->getDateRangeLabelProperty(),
        ])->layout('components.layouts.owner');
    }
}
