<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="min-w-0">
                <div class="flex items-center gap-3">
                    <a href="{{ route('livewire.employee.dashboard') }}" class="text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-slate-100">Stock History</h1>
                        <p class="text-sm text-gray-500 dark:text-slate-400 break-words">
                            {{ $branch->name }} • {{ $stockHistories->count() }} movements
                        </p>
                    </div>
                </div>
            </div>
            <a href="{{ route('livewire.employee.inventory') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Manage Stock
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Search</label>
                    <div class="relative">
                        <div
                            class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.400ms="search"
                            placeholder="Search by product name or reason..."
                            class="w-full h-10 pl-10 pr-12 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:placeholder-slate-400 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        @if(!empty($search))
                        <button wire:click="clearSearch" type="button"
                            class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Filter dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Show</label>
                    <select wire:model.live="scopeFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100">
                        <option value="all" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">All Movements (Branch)</option>
                        <option value="manual" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Manual Movements Only</option>
                        <option value="mine" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">My Movements Only</option>
                    </select>
                </div>
            </div>

            @if($search || $scopeFilter !== 'all')
            <div class="flex justify-end mt-3">
                <button wire:click="resetFilters" class="text-xs text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 underline">
                    Reset filters
                </button>
            </div>
            @endif
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap items-center gap-3 mb-4 text-xs text-gray-600 dark:text-slate-300">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Stock In
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Stock Out
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Adjustment
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Customer Order
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Order Cancelled / Not Picked Up
            </span>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            @if($stockHistories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Type</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Product</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-slate-300">Old</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-slate-300">Change</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-slate-300">After</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Reason</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">By</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @foreach($stockHistories as $row)
                        @php
                        $typeLabels = [
                        'stock_in' => ['label' => 'Stock In', 'color' => 'green'],
                        'stock_out' => ['label' => 'Stock Out', 'color' => 'red'],
                        'adjustment' => ['label' => 'Adjustment', 'color' => 'yellow'],
                        'out' => ['label' => 'Customer Order', 'color' => 'blue'],
                        'cancelled' => ['label' => 'Order Cancelled', 'color' => 'orange'],
                        'no_show' => ['label' => 'Not Picked Up', 'color' => 'orange'],
                        ];
                        $info = $typeLabels[$row->type] ?? ['label' => ucfirst($row->type), 'color' => 'gray'];
                        $badgeClasses = match ($info['color']) {
                        'green' => 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200',
                        'red' => 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200',
                        'yellow' => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200',
                        'blue' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200',
                        'orange' => 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-200',
                        default => 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100',
                        };
                        $qty = (int) $row->quantity;

                        $reason = $row->notes ?? '';
                        if ($row->kind === 'order') {
                        if (preg_match('/Order #(ORD-[A-Z0-9]+)/', $reason, $m)) {
                        $orderNo = $m[1];
                        } else {
                        $orderNo = null;
                        }
                        $reason = match ($row->type) {
                        'out' => $orderNo ? "Customer order — {$orderNo}" : 'Customer order',
                        'cancelled' => $orderNo ? "Order {$orderNo} was cancelled" : 'Order cancelled',
                        'no_show' => $orderNo ? "Order {$orderNo} was not picked up" : 'Order not picked up',
                        default => $reason,
                        };
                        }
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-4 py-3">
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap {{ $badgeClasses }}">
                                    {{ $info['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100 break-words">
                                {{ $row->product->name ?? 'Deleted product' }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-slate-400">{{ $row->old_stock }}</td>
                            <td class="px-4 py-3 text-right font-semibold
                                {{ $qty > 0 ? 'text-green-600' : ($qty < 0 ? 'text-red-600' : 'text-gray-500 dark:text-slate-400') }}">
                                {{ $qty > 0 ? '+' : '' }}{{ $qty }}
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-slate-100">{{ $row->new_stock }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-400 max-w-xs truncate" title="{{ $reason }}">
                                {{ $reason ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300 text-xs">
                                {{ $row->user->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-3 text-gray-400 dark:text-slate-500 text-xs whitespace-nowrap">
                                {{ $row->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 sm:py-16 text-gray-500 dark:text-slate-400">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-lg font-medium">No stock movements found</p>
                <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">
                    @if($search || $scopeFilter !== 'all')
                    Try adjusting your filters.
                    @else
                    Stock movements will appear here once products are created or ordered.
                    @endif
                </p>
            </div>
            @endif
        </div>

        @if($stockHistories->count() > 0)
        <p class="mt-3 text-xs text-gray-400 dark:text-slate-500 text-right">
            Showing {{ $stockHistories->count() }} {{ Str::plural('movement', $stockHistories->count()) }}
        </p>
        @endif
    </div>
</div>