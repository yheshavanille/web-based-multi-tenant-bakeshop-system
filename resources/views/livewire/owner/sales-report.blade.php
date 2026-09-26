<div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('livewire.owner.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Sales Report</h1>
                    <p class="text-sm text-gray-500">
                        {{ $periodLabel }} • {{ $dateRangeLabel }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Period tabs + Month picker -->
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1.5 inline-flex flex-wrap gap-1">
                <button wire:click="setPeriod('today')" class="px-4 py-2 text-sm font-medium rounded-lg transition
                {{ $period === 'today' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    📅 Today
                </button>
                <button wire:click="setPeriod('week')" class="px-4 py-2 text-sm font-medium rounded-lg transition
                {{ $period === 'week' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    📅 This Week
                </button>
                <button wire:click="setPeriod('month')" class="px-4 py-2 text-sm font-medium rounded-lg transition
                {{ $period === 'month' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    📅 Month
                </button>
                <button wire:click="setPeriod('year')" class="px-4 py-2 text-sm font-medium rounded-lg transition
                {{ $period === 'year' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    📅 This Year
                </button>
            </div>

            {{-- Month picker — only visible when the "Month" tab is active --}}
            @if($period === 'month')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1.5 inline-flex items-center gap-2">
                <span class="text-sm text-gray-500 pl-2">📆 Select Month:</span>
                <select wire:change="setMonth($event.target.value)"
                    class="px-3 py-2 pr-10 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm appearance-none bg-white bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat">
                    @foreach($availableMonths as $month)
                    <option value="{{ $month['value'] }}" {{ $selectedMonth===$month['value'] ? 'selected' : '' }}>
                        {{ $month['label'] }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <!-- Summary cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Revenue</p>
                    <span class="text-2xl">💰</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">₱{{ number_format($totalRevenue, 2) }}</p>
                <p class="text-xs text-green-600 mt-1">{{ $periodLabel }}</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Orders</p>
                    <span class="text-2xl">📋</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalOrders) }}</p>
                <p class="text-xs text-blue-600 mt-1">Completed</p>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border border-purple-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Avg Order Value</p>
                    <span class="text-2xl">📈</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">₱{{ number_format($averageOrderValue, 2) }}</p>
                <p class="text-xs text-purple-600 mt-1">Per completed order</p>
            </div>
        </div>

        <!-- Branch breakdown -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xl">🏪</span>
                <h2 class="text-lg font-semibold text-gray-800">Branch Breakdown</h2>
                <span class="text-xs text-gray-500 ml-auto">{{ $periodLabel }}</span>
            </div>

            @if(count($branchBreakdown) > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Revenue</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Orders</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Avg Order</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Share</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($branchBreakdown as $branch)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">📍 {{ $branch['name'] }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                ₱{{ number_format($branch['revenue'], 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $branch['orders'] }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">₱{{ number_format($branch['avg'], 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                @if($totalRevenue > 0)
                                @php $share = round(($branch['revenue'] / $totalRevenue) * 100, 1); @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $share }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-10 text-right">{{ $share }}%</span>
                                </div>
                                @else
                                <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td class="px-4 py-3 text-gray-800">Total</td>
                            <td class="px-4 py-3 text-right text-green-700">₱{{ number_format($totalRevenue, 2) }}</td>
                            <td class="px-4 py-3 text-right text-gray-700">{{ $totalOrders }}</td>
                            <td class="px-4 py-3 text-right text-gray-700">₱{{ number_format($averageOrderValue, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>No branches found.</p>
            </div>
            @endif
        </div>

        <!-- Best sellers -->
        @if(count($bestSellers) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xl">🏆</span>
                <h2 class="text-lg font-semibold text-gray-800">Best Sellers</h2>
                <span class="text-xs text-gray-500 ml-auto">Top 5 for {{ $periodLabel }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Sold</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($bestSellers as $index => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $item['product']['name'] ?? 'Product Unavailable' }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $item['total_sold'] }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                ₱{{ number_format($item['total_revenue'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ✅ Daily Trend — Line Chart -->
        @if(count($dailyTrend) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
            wire:key="trend-chart-{{ $period }}-{{ $selectedMonth }}-{{ $trendGrouping }}">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xl">📊</span>
                <h2 class="text-lg font-semibold text-gray-800">Daily Trend</h2>
                <span class="text-xs text-gray-500 ml-auto">
                    {{ $periodLabel }} • {{ $trendGrouping === 'month' ? 'Grouped by month' : 'Grouped by day' }}
                </span>
            </div>
            <div class="relative" style="height: 320px;">
                <canvas id="revenueTrendChart" data-labels='@json($chartData["labels"])'
                    data-revenue='@json($chartData["revenue"])' data-orders='@json($chartData["orders"])'></canvas>
            </div>
        </div>
        @endif

        <!-- ✅ Revenue Trend — Table -->
        @if(count($dailyTrend) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xl">📈</span>
                <h2 class="text-lg font-semibold text-gray-800">Revenue Trend</h2>
                <span class="text-xs text-gray-500 ml-auto">
                    {{ $periodLabel }} • {{ $trendGrouping === 'month' ? 'Grouped by month' : 'Grouped by day' }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">
                                {{ $trendGrouping === 'month' ? 'Month' : 'Date' }}
                            </th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Orders</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Revenue</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Visual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @php
                        $maxRevenue = collect($dailyTrend)->max('revenue') ?: 1;
                        @endphp
                        @foreach($dailyTrend as $row)
                        <tr>
                            <td class="px-4 py-3 text-gray-700 font-medium">
                                {{ $row['label'] }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $row['orders'] }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                ₱{{ number_format($row['revenue'], 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-full bg-gray-200 rounded-full h-2 max-w-xs">
                                    <div class="bg-amber-500 h-2 rounded-full"
                                        style="width: {{ round(($row['revenue'] / $maxRevenue) * 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    {{-- ✅ Chart.js initialization --}}
    <script>
        (function () {
                let chartInstance = null;

                function initChart() {
                    const canvas = document.getElementById('revenueTrendChart');
                    if (!canvas) return;

                    if (chartInstance) {
                        chartInstance.destroy();
                        chartInstance = null;
                    }

                    const labels = JSON.parse(canvas.dataset.labels || '[]');
                    const revenue = JSON.parse(canvas.dataset.revenue || '[]');
                    const orders = JSON.parse(canvas.dataset.orders || '[]');

                    if (labels.length === 0) return;

                    chartInstance = new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Revenue (₱)',
                                    data: revenue,
                                    borderColor: '#d97706',
                                    backgroundColor: 'rgba(217, 119, 6, 0.1)',
                                    fill: true,
                                    tension: 0.3,
                                    borderWidth: 2,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#d97706',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    yAxisID: 'y',
                                },
                                {
                                    label: 'Orders',
                                    data: orders,
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    fill: false,
                                    tension: 0.3,
                                    borderWidth: 2,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#3b82f6',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    yAxisID: 'y1',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 8,
                                        font: { size: 12 }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            const label = context.dataset.label || '';
                                            const value = context.parsed.y;
                                            if (label.includes('Revenue')) {
                                                return label + ': ₱' + value.toLocaleString('en-PH', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            }
                                            return label + ': ' + value;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function (value) {
                                            return '₱' + value.toLocaleString();
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Revenue',
                                        color: '#d97706'
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    beginAtZero: true,
                                    grid: { drawOnChartArea: false },
                                    title: {
                                        display: true,
                                        text: 'Orders',
                                        color: '#3b82f6'
                                    }
                                }
                            }
                        }
                    });
                }

                document.addEventListener('DOMContentLoaded', initChart);
                document.addEventListener('livewire:navigated', () => {
                    setTimeout(initChart, 50);
                });
                document.addEventListener('livewire:init', () => {
                    Livewire.hook('morph.updated', ({ el }) => {
                        if (el.id === 'revenueTrendChart' || el.querySelector?.('#revenueTrendChart')) {
                            setTimeout(initChart, 50);
                        }
                    });
                });
            })();
    </script>
</div>