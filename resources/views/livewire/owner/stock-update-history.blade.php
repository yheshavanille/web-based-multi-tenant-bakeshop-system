<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Stock Update History</h1>
                <p class="text-sm text-gray-500">Trace every stock movement across your branches</p>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div
                            class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.400ms="search"
                            placeholder="Search by product name..."
                            class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        @if(!empty($search))
                        <button wire:click="clearSearch" type="button"
                            class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Movement Type</label>
                    <select wire:model.live="typeFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                        <option value="all">All Movements</option>
                        <option value="manual">Manual Only</option>
                        <option value="order">Orders Only</option>
                        <option value="stock_in">Stock In</option>
                        <option value="stock_out">Stock Out</option>
                        <option value="adjustment">Adjustment</option>
                        <option value="order_sale">Customer Order</option>
                        <option value="cancelled">Order Cancelled</option>
                        <option value="no_show">Not Picked Up</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select wire:model.live="branchFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                        <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($search || $typeFilter !== 'all' || $branchFilter !== 'all')
            <div class="flex justify-end mt-3">
                <button wire:click="resetFilters" class="text-xs text-gray-500 hover:text-gray-700 underline">
                    Reset filters
                </button>
            </div>
            @endif
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap items-center gap-3 mb-4 text-xs text-gray-600">
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

        <!-- Movement log -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($histories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <the<tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-700">Old Stock</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-700">Change</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-700">Stock After</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Reason</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">By</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">When</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($histories as $row)
                            @php
                            // Map type/status → label + badge color
                            $typeLabels = [
                            'stock_in' => ['label' => 'Stock In', 'color' => 'green'],
                            'stock_out' => ['label' => 'Stock Out', 'color' => 'red'],
                            'adjustment' => ['label' => 'Adjustment', 'color' => 'yellow'],
                            'out' => ['label' => 'Customer Order', 'color' => 'blue'],
                            'cancelled' => ['label' => 'Order Cancelled', 'color' => 'orange'],
                            'no_show' => ['label' => 'Not Picked Up', 'color' => 'orange'],
                            ];
                            $info = $typeLabels[$row['type']] ?? ['label' => ucfirst($row['type']), 'color' => 'gray'];
                            $badgeClasses = match ($info['color']) {
                            'green' => 'bg-green-100 text-green-800',
                            'red' => 'bg-red-100 text-red-800',
                            'yellow' => 'bg-yellow-100 text-yellow-800',
                            'blue' => 'bg-blue-100 text-blue-800',
                            'orange' => 'bg-orange-100 text-orange-800',
                            default => 'bg-gray-100 text-gray-800',
                            };
                            $qty = (int) $row['quantity'];

                            // ✅ Clean up the reason text for order-driven rows
                            $reason = $row['notes'] ?? '';

                            if ($row['kind'] === 'order') {
                            // Extract the order number from the notes (e.g. "Order #ORD-XXX — stock reduced")
                            if (preg_match('/Order #(ORD-[A-Z0-9]+)/', $reason, $m)) {
                            $orderNo = $m[1];
                            } else {
                            $orderNo = null;
                            }

                            $reason = match ($row['type']) {
                            'out' => $orderNo ? "Customer order — {$orderNo}" : 'Customer order',
                            'cancelled' => $orderNo ? "Order {$orderNo} was cancelled" : 'Order cancelled',
                            'no_show' => $orderNo ? "Order {$orderNo} was not picked up" : 'Order not picked up',
                            default => $reason,
                            };
                            }
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap {{ $badgeClasses }}">
                                        {{ $info['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $row['product']->name ?? 'Deleted product' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $row['branch']->name ?? 'Deleted branch' }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-500">
                                    {{ $row['old_stock'] }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold
    {{ $qty > 0 ? 'text-green-600' : ($qty < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                    {{ $qty > 0 ? '+' : '' }}{{ $qty }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-800">
                                    {{ $row['new_stock'] }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 max-w-xs truncate" title="{{ $reason }}">
                                    {{ $reason ?: '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    {{ $row['user']->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                                    {{ $row['created_at']->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-16 text-gray-500">
                <span class="text-5xl block mb-3">📭</span>
                <p class="text-lg font-medium">No stock movements found</p>
                <p class="text-sm text-gray-400 mt-1">
                    @if($search || $typeFilter !== 'all' || $branchFilter !== 'all')
                    Try adjusting your filters.
                    @else
                    Stock movements will appear here once products are created or ordered.
                    @endif
                </p>
            </div>
            @endif
        </div>

        @if($histories->count() > 0)
        <p class="mt-3 text-xs text-gray-400 text-right">
            Showing {{ $histories->count() }} {{ Str::plural('movement', $histories->count()) }}
        </p>
        @endif
    </div>
</div>