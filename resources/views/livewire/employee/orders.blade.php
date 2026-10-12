<div>
    <div class="max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-slate-100">
                    Orders Dashboard
                </h1>

                <p class="text-sm text-gray-500 dark:text-slate-400 inline-flex flex-wrap items-center gap-1.5">
                    Manage orders for
                    <span class="inline-flex items-center gap-1 font-medium text-amber-600 dark:text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $branch->name }}
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('livewire.employee.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 rounded-lg transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
                <select wire:model.live="selectedStatus"
                    class="px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm appearance-none bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat pr-10">
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="all">All Orders</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="pending">Pending</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="preparing">Preparing</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="ready_for_pickup">Ready</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="completed">Completed</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="partially_completed">Partially Completed</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="no_show">No Show</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100" value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search by order #, customer name, or product..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm dark:bg-slate-700 dark:text-slate-100 dark:placeholder-slate-400">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 dark:text-slate-500 hover:text-gray-600 transition"
                    style="position: absolute;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500 dark:text-slate-400 break-words">
                Showing results for: <span class="font-medium text-amber-600 dark:text-amber-400">{{ $search }}</span>
                <span class="text-gray-400 dark:text-slate-500">({{ $orders->total() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-200 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Orders Container -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">

            @if($orders->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">

                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Items</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Total</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">

                        @foreach($orders as $order)

                        <tr class="bg-gray-50 dark:bg-slate-900">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300 break-words">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300 whitespace-nowrap">{{ $order->items->sum('quantity') }} items</td>
                            <td class="px-4 py-3 font-medium text-amber-600 dark:text-amber-400">
                                ₱{{ number_format($order->total_amount, 2) }}
                                @if($order->tax_amount)
                                <span class="text-xs text-gray-400 dark:text-slate-500 block">
                                    (incl. VAT ₱{{ number_format($order->tax_amount, 2) }})
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="openDetailsModal({{ $order->id }})"
                                    class="text-xs text-blue-600 dark:text-blue-200 hover:text-blue-800 font-medium transition whitespace-nowrap">
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700">
                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2 px-4 mb-4">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-slate-300">Order Items</span>
                                        <div class="w-full sm:w-[340px] flex justify-center">
                                            <span class="text-sm font-semibold text-gray-700 dark:text-slate-300">Status</span>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($order->items as $item)
                                        <div
                                            class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 dark:border-slate-700 pb-2 last:border-0 px-4">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-800 dark:text-slate-100 break-words">
                                                    {{ $item->product?->name ?? 'Product Unavailable' }}
                                                </p>

                                                <p
                                                    class="text-xs text-gray-500 dark:text-slate-400 inline-flex items-center gap-1 flex-wrap">
                                                    <span>Qty: {{ $item->quantity }}</span>
                                                    @if($item->original_price && $item->original_price > $item->price)
                                                    <span class="text-red-600 font-medium">₱{{
                                                        number_format($item->price, 2) }}</span>
                                                    <span class="text-gray-400 dark:text-slate-500 line-through">₱{{
                                                        number_format($item->original_price, 2) }}</span>
                                                    @else
                                                    <span>x ₱{{ number_format($item->price, 2) }}</span>
                                                    @endif

                                                    @if($item->pickup_time)
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i
                                                        A') }}
                                                    </span>
                                                    @endif
                                                </p>

                                                @if($item->notes)
                                                <p
                                                    class="text-xs text-amber-600 dark:text-amber-400 italic mt-0.5 inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    {{ $item->notes }}
                                                </p>
                                                @endif

                                                @if($item->status === 'cancelled' && $item->cancellation_reason)
                                                <p class="text-xs text-red-600 italic mt-0.5">
                                                    Cancelled — {{ $item->cancellation_reason }}
                                                </p>
                                                @endif
                                            </div>

                                            <div class="w-full sm:w-[340px] flex flex-wrap items-center justify-end gap-4">
                                                <span
                                                    class="inline-flex items-center justify-center text-xs px-3 py-1 rounded-full whitespace-nowrap font-medium min-w-[110px]
                                                    {{ $item->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                                                    {{ $item->status === 'preparing' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200' : '' }}
                                                    {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                                                    {{ $item->status === 'completed' ? 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100' : '' }}
                                                    {{ $item->status === 'no_show' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                                                    {{ $item->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                </span>

                                                <div class="relative">
                                                        <select
    wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
    wire:loading.attr="disabled"
    wire:target="updateItemStatus"
    x-data x-on:change="if ($event.target.value === 'cancelled') {
        if (!confirm('⚠️ Are you sure you want to cancel this item?')) {
            $event.target.value = '{{ $item->status }}';
            $event.preventDefault();
        }
    }" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;"
    class="pl-3 pr-8 py-1.5 text-xs border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100 cursor-pointer">
                                                        <option value="pending" {{ $item->status === 'pending' ?
                                                            'selected' : '' }}>Pending</option>
                                                        <option value="preparing" {{ $item->status === 'preparing' ?
                                                            'selected' : '' }}>Preparing</option>
                                                        <option value="ready_for_pickup" {{ $item->status ===
                                                            'ready_for_pickup' ? 'selected' : '' }}>Ready</option>
                                                        <option value="completed" {{ $item->status === 'completed' ?
                                                            'selected' : '' }}>Completed</option>
                                                        <option value="no_show" {{ $item->status === 'no_show' ?
                                                            'selected' : '' }}>No Show</option>
                                                        <option value="cancelled" {{ $item->status === 'cancelled' ?
                                                            'selected' : '' }}>Cancelled</option>
                                                    </select>
                                                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 text-gray-400 dark:text-slate-500 pointer-events-none"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
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
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
                @if(!empty($search))
                <p>No orders found matching "<span class="font-medium text-amber-600 dark:text-amber-400 break-words">{{ $search }}</span>"</p>
                <p class="text-xs text-gray-400 dark:text-slate-500">Try adjusting your search or status filter.</p>
                @else
                <p>No orders for this branch yet.</p>
                @endif
            </div>

            @endif

        </div>

    </div>

    <!-- ✅ Order Details Modal -->
    @if($showDetailsModal && $selectedOrderDetails)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeDetailsModal"></div>

        <div class="relative z-10 w-full max-w-5xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 92vh;">

            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-amber-50 dark:bg-slate-800 flex-shrink-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-slate-100">Order Details</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400 break-words">
                            #{{ $selectedOrderDetails->order_number }} •
                            {{ $selectedOrderDetails->branch->name ?? 'N/A' }} •
                            {{ $selectedOrderDetails->customer->name ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-gray-400 dark:text-slate-500 hover:text-gray-600 transition flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Order Date</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{ $selectedOrderDetails->created_at->format('M d,
                            Y h:i A') }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Status</p>
                        <span class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrderDetails->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrderDetails->status === 'preparing' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200' : '' }}
                            {{ $selectedOrderDetails->status === 'ready_for_pickup' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                            {{ $selectedOrderDetails->status === 'completed' ? 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100' : '' }}
                            {{ $selectedOrderDetails->status === 'partially_completed' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrderDetails->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $selectedOrderDetails->status)) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Payment Method</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{ $selectedOrderDetails->payment_method_label }}
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Payment Status</p>
                        <span
                            class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrderDetails->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                            {{ $selectedOrderDetails->payment_status === 'partially_paid' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrderDetails->payment_status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                            {{ $selectedOrderDetails->payment_status === 'refunded' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                            {{ $selectedOrderDetails->payment_status === 'cancelled' ? 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300' : '' }}">
                            {{ ucfirst($selectedOrderDetails->payment_status) }}
                        </span>
                    </div>
                </div>

                {{-- ✅ Cancellation reason banner --}}
                @if($selectedOrderDetails->status === 'cancelled')
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-3 flex items-start gap-2">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-200">
                            @if($selectedOrderDetails->cancellation_reason)
                            Cancelled — {{ $selectedOrderDetails->cancellation_reason }}
                            @else
                            This order was cancelled
                            @endif
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-300 mt-0.5">
                            @if($selectedOrderDetails->cancelled_by === 'customer')
                            Cancelled by customer.
                            @elseif($selectedOrderDetails->cancelled_by === 'employee')
                            Cancelled by staff.
                            @else
                            No cancellation reason was recorded.
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3 inline-flex items-center gap-2">

                        Order Items
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Product</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Qty</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Price</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Original</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Subtotal</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Status</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-slate-300">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @foreach($selectedOrderDetails->items as $item)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-slate-100 break-words">{{ $item->product->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-slate-300">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">
                                        @if($item->original_price && $item->original_price > $item->price)
                                        <span class="text-red-600 font-medium">₱{{ number_format($item->price, 2)
                                            }}</span>
                                        @else
                                        <span class="text-gray-600 dark:text-slate-300">₱{{ number_format($item->price, 2) }}</span>
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
                                    <td class="px-4 py-2 text-gray-600 dark:text-slate-300">₱{{ number_format($item->price *
                                        $item->quantity, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            {{ $item->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                                            {{ $item->status === 'preparing' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200' : '' }}
                                            {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                                            {{ $item->status === 'completed' ? 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100' : '' }}
                                            {{ $item->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                                            {{ $item->status === 'no_show' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-slate-300 text-xs break-words">
                                        @if($item->notes)
                                        <span class="italic">"{{ $item->notes }}"</span>
                                        @else
                                        <span class="text-gray-400 dark:text-slate-500">—</span>
                                        @endif
                                        @if($item->status === 'cancelled' && $item->cancellation_reason)
                                        <p class="text-red-600 dark:text-red-300 italic mt-0.5">Cancelled — {{ $item->cancellation_reason
                                            }}</p>
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
                                            class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            Original Order
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Subtotal:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['original_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['original_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-gray-800 dark:text-slate-100">
                                        Original Total:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['original_total'], 2) }}</td>
                                </tr>

                                @if($b['amount_charged'] > 0)
                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                        <div
                                            class="text-xs font-semibold text-green-600 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Charged to Customer
                                        </div>
                                    </td>
                                </tr>
                                @foreach($b['charged_items'] as $item)
                                <tr>
                                    <td colspan="5" class="px-4 py-0.5 text-right text-xs text-gray-500 dark:text-slate-400">• {{
                                        $item['name'] }} ({{ $item['quantity'] }}x)</td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600 dark:text-slate-300">₱{{
                                        number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Completed Items:
                                    </td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-green-700 dark:text-green-200">
                                        Amount Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-green-600">₱{{
                                        number_format($b['amount_charged'], 2) }}</td>
                                </tr>
                                @endif

                                @if($b['amount_not_charged'] > 0)
                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                        <div
                                            class="text-xs font-semibold text-red-600 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                </path>
                                            </svg>
                                            Not Charged
                                        </div>
                                    </td>
                                </tr>
                                @foreach($b['not_charged_items'] as $item)
                                <tr>
                                    <td colspan="5" class="px-4 py-0.5 text-right text-xs text-gray-500 dark:text-slate-400">• {{
                                        $item['name'] }} ({{ $item['quantity'] }}x)</td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600 dark:text-slate-300">₱{{
                                        number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">No Show /
                                        Cancelled:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['not_charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['not_charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-red-700 dark:text-red-200">
                                        Amount Not Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-red-600">₱{{
                                        number_format($b['amount_not_charged'], 2) }}</td>
                                </tr>
                                @endif

                                @if($b['amount_outstanding'] > 0)
                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                        <div
                                            class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Outstanding
                                        </div>
                                    </td>
                                </tr>
                                @foreach($b['outstanding_items'] as $item)
                                <tr>
                                    <td colspan="5" class="px-4 py-0.5 text-right text-xs text-gray-500 dark:text-slate-400">• {{
                                        $item['name'] }} ({{ $item['quantity'] }}x)</td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600 dark:text-slate-300">₱{{
                                        number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Pending +
                                        Preparing + Ready:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['outstanding_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                        number_format($b['outstanding_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-amber-700 dark:text-amber-200">
                                        Amount Outstanding:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-amber-600 dark:text-amber-400">₱{{
                                        number_format($b['amount_outstanding'], 2) }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if($selectedOrderDetails->pickup_time)
                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pickup Details
                    </h4>
                    <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                        <p class="text-sm text-gray-700 dark:text-slate-300 break-words">
                            <span class="font-medium">Branch:</span> {{ $selectedOrderDetails->branch->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-700 dark:text-slate-300">
                            <span class="font-medium">Pickup Time:</span> {{
                            \Carbon\Carbon::parse($selectedOrderDetails->pickup_time)->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
                @endif

            </div>

            <div class="px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex-shrink-0 flex justify-end">
                <button wire:click="closeDetailsModal"
                    class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
