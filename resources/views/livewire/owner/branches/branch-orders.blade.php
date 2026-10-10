<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex flex-wrap gap-2 items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('livewire.owner.dashboard') }}"
                        class="text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-slate-100 break-words">All
                            Orders</h1>
                        <p class="text-sm text-gray-500 dark:text-slate-400">
                            {{ $branch->name }} • {{ $orders->total() }} total orders
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search by order #, customer name, or product..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-200 transition"
                    style="position: absolute;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>

            <select wire:model.live="selectedStatus"
                class="px-4 py-2 pr-10 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm appearance-none bg-white dark:bg-slate-800 dark:text-slate-100 dark:[color-scheme:dark] bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat min-w-[150px]">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="preparing">Preparing</option>
                <option value="ready_for_pickup">Ready</option>
                <option value="completed">Completed</option>
                <option value="partially_completed">Partially Completed</option>
                <option value="no_show">No Show</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        @if(!empty($search))
        <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
            Showing results for: <span class="font-medium text-amber-600 dark:text-amber-400">{{ $search }}</span>
            <span class="text-gray-400 dark:text-slate-500">({{ $orders->count() }} found)</span>
        </p>
        @endif

        <!-- Orders Table -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Items</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Amount</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Date</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">#{{ $order->order_number
                                }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $order->customer->name ?? 'N/A'
                                }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $order->item_count }} items</td>
                            <td class="px-4 py-3 font-semibold text-green-600 dark:text-green-400" title="Includes VAT">
                                ₱{{ number_format($order->adjusted_total ?? $order->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-400">{{ $order->created_at->format('M d,
                                Y h:i A') }}
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="viewOrderDetails({{ $order->id }})"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 font-medium">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- ✅ Pagination --}}
            @if($orders->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
                {{ $orders->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-12 text-gray-500 dark:text-slate-400">
                @if(!empty($search))
                <p>No orders found matching "<span class="font-medium text-amber-600 dark:text-amber-400">{{ $search
                        }}</span>"</p>
                <p class="text-xs text-gray-400 dark:text-slate-500">Try adjusting your search.</p>
                @else
                <p>No orders for this branch yet.</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- ✅ Order Details Modal -->
    @if($showOrderDetails && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
        style="overscroll-behavior: contain;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeOrderDetails"></div>

        <div class="relative z-10 w-full max-w-5xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 92vh;">

            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-blue-50 dark:bg-blue-900/30 dark:bg-blue-900/30 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-slate-100">Order Details</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400">
                            #{{ $selectedOrder->order_number }} •
                            {{ $selectedOrder->branch->name ?? 'N/A' }} •
                            {{ $selectedOrder->customer->name ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="closeOrderDetails"
                        class="text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Order Date</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{
                            $selectedOrder->created_at->format('M d, Y
                            h:i A') }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Status</p>
                        <span
                            class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrder->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrder->status === 'preparing' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200' : '' }}
                            {{ $selectedOrder->status === 'ready_for_pickup' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                            {{ $selectedOrder->status === 'completed' ? 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100' : '' }}
                            {{ $selectedOrder->status === 'partially_completed' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrder->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                            {{ $selectedOrder->status === 'no_show' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $selectedOrder->status)) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Payment Method</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{
                            $selectedOrder->payment_method_label }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Payment Status</p>
                        <span
                            class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrder->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                            {{ $selectedOrder->payment_status === 'partially_paid' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrder->payment_status === 'refunded' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                            {{ $selectedOrder->payment_status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}">
                            {{ ucfirst($selectedOrder->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"> Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">
                                        Product</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Qty
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Price
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">
                                        Original</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">
                                        Subtotal</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Status
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Notes
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @foreach($selectedOrder->items as $item)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-slate-100">{{
                                        $item->product->name ?? 'N/A'
                                        }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-slate-300">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">
                                        @if($item->original_price && $item->original_price > $item->price)
                                        <span class="text-red-600 dark:text-red-400 font-medium">₱{{
                                            number_format($item->price, 2)
                                            }}</span>
                                        @else
                                        <span class="text-gray-600 dark:text-slate-300">₱{{ number_format($item->price,
                                            2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($item->original_price && $item->original_price > $item->price)
                                        <span class="text-gray-400 dark:text-slate-500 line-through">₱{{
                                            number_format($item->original_price, 2) }}</span>
                                        @else
                                        <span class="text-gray-400 dark:text-slate-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-slate-300">₱{{
                                        number_format($item->price *
                                        $item->quantity, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full
                                            {{ $item->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                                            {{ $item->status === 'preparing' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200' : '' }}
                                            {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                                            {{ $item->status === 'completed' ? 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100' : '' }}
                                            {{ $item->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                                            {{ $item->status === 'no_show' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-slate-300 text-xs">
                                        @if($item->notes)
                                        <span class="italic">"{{ $item->notes }}"</span>
                                        @else
                                        <span class="text-gray-400 dark:text-slate-500">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-slate-900">
                                @php $b = $this->getBreakdown(); @endphp

                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1">
                                        <div
                                            class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                            📦
                                            Original Order</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Subtotal:
                                    </td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['original_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT
                                        (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['original_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm font-semibold text-gray-800 dark:text-slate-100">
                                        Original Total:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['original_total'], 2) }}</td>
                                </tr>

                                @if($b['amount_charged'] > 0)
                                <tr>
                                    <td colspan="7"
                                        class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                        <div
                                            class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            ✅
                                            Charged to Customer</div>
                                    </td>
                                </tr>
                                @foreach($b['charged_items'] as $item)
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-0.5 text-right text-xs text-gray-500 dark:text-slate-400">
                                        • {{ $item['name'] }} ({{ $item['quantity'] }}x)
                                    </td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600 dark:text-slate-300">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Completed
                                        Items:
                                    </td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT
                                        (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm font-semibold text-green-700 dark:text-green-300">
                                        Amount Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-green-600 dark:text-green-400">₱{{
                                        number_format($b['amount_charged'], 2) }}</td>
                                </tr>
                                @endif

                                @if($b['amount_not_charged'] > 0)
                                <tr>
                                    <td colspan="7"
                                        class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                        <div
                                            class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">
                                            ❌ Not
                                            Charged</div>
                                    </td>
                                </tr>
                                @foreach($b['not_charged_items'] as $item)
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-0.5 text-right text-xs text-gray-500 dark:text-slate-400">
                                        • {{ $item['name'] }} ({{ $item['quantity'] }}x)
                                    </td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600 dark:text-slate-300">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">No Show /
                                        Cancelled:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['not_charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT
                                        (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['not_charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm font-semibold text-red-700 dark:text-red-300">
                                        Amount Not Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-red-600 dark:text-red-400">₱{{
                                        number_format($b['amount_not_charged'], 2) }}</td>
                                </tr>
                                @endif

                                @if($b['amount_outstanding'] > 0)
                                <tr>
                                    <td colspan="7"
                                        class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                        <div
                                            class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">
                                            ⏳
                                            Outstanding</div>
                                    </td>
                                </tr>
                                @foreach($b['outstanding_items'] as $item)
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-0.5 text-right text-xs text-gray-500 dark:text-slate-400">
                                        • {{ $item['name'] }} ({{ $item['quantity'] }}x)
                                    </td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600 dark:text-slate-300">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Pending +
                                        Preparing + Ready:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['outstanding_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT
                                        (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['outstanding_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-1 text-right text-sm font-semibold text-amber-700 dark:text-amber-300">
                                        Amount Outstanding:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-amber-600 dark:text-amber-400">₱{{
                                        number_format($b['amount_outstanding'], 2) }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if($selectedOrder->pickup_time)
                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">📍 Pickup Details</h4>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-sm text-gray-700 dark:text-slate-300">
                            <span class="font-medium">Branch:</span> {{ $selectedOrder->branch->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-700 dark:text-slate-300">
                            <span class="font-medium">Pickup Time:</span> {{
                            \Carbon\Carbon::parse($selectedOrder->pickup_time)->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
                @endif

            </div>

            <div
                class="px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex-shrink-0 flex justify-end">
                <button wire:click="closeOrderDetails"
                    class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>