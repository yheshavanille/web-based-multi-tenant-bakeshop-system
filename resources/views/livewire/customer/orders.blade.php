<div>
    <div class="max-w-6xl mx-auto">
        <!-- Header with Back Button and Filter -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-slate-100">My Orders</h1>
                <p class="text-sm text-gray-500 dark:text-slate-400">Track your order status</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('livewire.customer.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <select wire:model.live="selectedStatus"
                    class="category-select max-w-full px-4 py-2 pr-10 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm text-gray-800 dark:text-slate-100 appearance-none bg-white dark:bg-slate-800 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat min-w-[160px]">
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="all">All Orders</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="pending">Pending</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="preparing">Preparing</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="ready_for_pickup">Ready</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="completed">Completed</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="partially_completed">Partially Completed</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="cancelled">Cancelled</option>
                    <option class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100" value="no_show">No Show</option>
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
                <input type="text" wire:model.live="search" placeholder="Search by order #, shop, or branch..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-500 dark:text-slate-300 hover:text-gray-700 dark:hover:text-white transition"
                    style="position: absolute;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                Showing results for: <span class="font-medium text-amber-600 dark:text-amber-400">{{ $search }}</span>
                <span class="text-gray-400 dark:text-slate-500">({{ $orders->total() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg text-sm">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg text-sm">
            {{ session('error') }}
        </div>
        @endif

        <!-- Orders List -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Shop</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Items</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Total</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Pickup Time</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300 w-40">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @foreach($orders as $order)
                        <tr class="bg-gray-50 dark:bg-slate-900">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">
                                @if($order->shop)
                                {{ $order->shop->shop_name }}
                                @else
                                <span class="text-gray-400 dark:text-slate-500">Shop Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">
                                @if($order->branch)
                                {{ $order->branch->name }}
                                @else
                                <span class="text-gray-400 dark:text-slate-500">Branch Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $order->items->count() }} items</td>
                            <td class="px-4 py-3 font-medium text-amber-600 dark:text-amber-400">₱{{ number_format($order->total_amount, 2)
                                }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3 w-40">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button wire:click="openDetailsModal({{ $order->id }})"
                                        class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 font-medium transition">
                                        View Details
                                    </button>
                                    @if($order->status === 'pending')
                                    <button wire:click="openCancelOrderModal({{ $order->id }})"
                                        class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 font-medium transition">
                                        Cancel Order
                                    </button>
                                    @elseif(in_array($order->status, ['completed', 'partially_completed']) &&
                                    !$order->serviceReview)
                                    @php
                                    $hasCompletedItem = $order->items->contains(fn($item) => $item->status ===
                                    'completed');
                                    @endphp
                                    @if($hasCompletedItem)
                                    <button wire:click="openReviewModal({{ $order->id }})"
                                        class="text-xs text-amber-600 dark:text-amber-400 hover:text-amber-800 font-medium transition">
                                        Leave Review
                                    </button>
                                    @endif
                                    @elseif(in_array($order->status, ['completed', 'partially_completed']) &&
                                    $order->serviceReview)
                                    @php
                                    $editCount = $order->serviceReview->edit_count ?? 0;
                                    $maxEdits = \App\Livewire\Customer\Orders::MAX_EDITS;
                                    $remaining = max(0, $maxEdits - $editCount);
                                    $isRemoved = $order->serviceReview->moderation_status === 'removed';
                                    $isFlagged = $order->serviceReview->moderation_status === 'pending_review';
                                    @endphp

                                    @if($isRemoved)
                                    <button wire:click="viewLockedReview({{ $order->id }})"
                                        class="inline-flex items-center gap-1 text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-200 font-medium transition px-2.5 py-1 rounded-lg"
                                        title="This review was removed by the Super Admin">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                            </path>
                                        </svg>
                                        Removed — View
                                    </button>
                                    @elseif($isFlagged)
                                    <button wire:click="viewLockedReview({{ $order->id }})"
                                        class="inline-flex items-center gap-1 text-xs bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 hover:bg-yellow-200 font-medium transition px-2.5 py-1 rounded-lg"
                                        title="This review is under review by the Super Admin">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        Under Review — View
                                    </button>
                                    @elseif($remaining > 0)
                                    <button wire:click="openEditReviewModal({{ $order->id }})"
                                        class="inline-flex items-center gap-1 text-xs bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 hover:bg-amber-200 font-medium transition px-2.5 py-1 rounded-lg"
                                        title="{{ $remaining }} edit(s) remaining">
                                        Edit Review
                                        <span
                                            class="text-[10px] bg-amber-200 text-amber-800 dark:text-amber-200 px-1.5 py-0.5 rounded-full">{{
                                            $remaining }} left</span>
                                    </button>
                                    @else
                                    <button wire:click="viewLockedReview({{ $order->id }})"
                                        class="inline-flex items-center gap-1 text-xs bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 font-medium transition px-2.5 py-1 rounded-lg"
                                        title="You have reached the edit limit">
                                        View Review
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center px-4">
                                        <span class="flex-1 text-sm font-bold text-gray-800 dark:text-slate-100">Order Items</span>
                                        <span class="w-40 pl-10 text-sm font-bold text-gray-800 dark:text-slate-100">Status</span>
                                    </div>
                                    @foreach($order->items as $item)
                                    <div class="flex items-center border-b border-gray-200 dark:border-slate-700 pb-2 last:border-0 px-4">
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-800 dark:text-slate-100">
                                                @if($item->product)
                                                {{ $item->product->name }}
                                                @else
                                                <span class="text-gray-400 dark:text-slate-500">Product Unavailable</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-slate-400 inline-flex items-center gap-2 flex-wrap">
                                                <span>Qty: {{ $item->quantity }}</span>
                                                @if($item->original_price && $item->original_price > $item->price)
                                                <span class="text-red-600 dark:text-red-400 font-medium">₱{{ number_format($item->price,
                                                    2) }}</span>
                                                <span class="text-gray-400 dark:text-slate-500 line-through">₱{{
                                                    number_format($item->original_price, 2) }}</span>
                                                @else
                                                <span>x ₱{{ number_format($item->price, 2) }}</span>
                                                @endif
                                                @if($item->pickup_time)
                                                <span class="inline-flex items-center gap-1 text-gray-500 dark:text-slate-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i A')
                                                    }}
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
                                        </div>
                                        <div class="w-40 pl-4 flex items-center gap-2">
                                            @if($item->status === 'pending')
                                            <button wire:click="openCancelItemModal({{ $item->id }})"
                                                class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition"
                                                title="Cancel this item">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            @endif
                                            <span class="text-xs px-2.5 py-1 rounded-full whitespace-nowrap {{ $item->status !== 'pending' ? 'ml-6' : '' }}
                                                {{ $item->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200' : '' }}
                                                {{ $item->status === 'preparing' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200' : '' }}
                                                {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200' : '' }}
                                                {{ $item->status === 'completed' ? 'bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100' : '' }}
                                                {{ $item->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}
                                                {{ $item->status === 'no_show' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
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
                <svg class="w-14 h-14 text-gray-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                @if(!empty($search))
                <p>No orders found matching "<span class="font-medium text-amber-600 dark:text-amber-400">{{ $search }}</span>"</p>
                <p class="text-xs text-gray-400 dark:text-slate-500">Try adjusting your search.</p>
                @else
                <p>No orders yet.</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- ✅ Order Details Modal -->
    <div x-data="{ open: false }" @open-details-modal.window="open = true" @close-details-modal.window="open = false">

        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false; $wire.closeDetailsModal()">
            </div>

            <div class="relative z-10 w-full max-w-5xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col"
                style="max-height: 92vh;" x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                @if($selectedOrderDetails)
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-amber-50 dark:bg-slate-800 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-slate-100">Order Details</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400">
                                #{{ $selectedOrderDetails->order_number }} •
                                {{ $selectedOrderDetails->branch->name ?? 'N/A' }} •
                                {{ $selectedOrderDetails->customer->name ?? 'N/A' }}
                            </p>
                        </div>
                        <button @click="open = false; $wire.closeDetailsModal()"
                            class="text-gray-500 dark:text-slate-300 hover:text-gray-700 dark:hover:text-white transition">
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
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{ $selectedOrderDetails->created_at->format('M
                                d, Y h:i A') }}</p>
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
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{ $selectedOrderDetails->payment_method_label
                                }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Payment Status</p>
                            <span class="text-sm font-medium px-2 py-0.5 rounded-full
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
                        <svg class="w-4 h-4 text-red-500 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
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
                            <p class="text-xs text-red-600 dark:text-red-400 mt-0.5">
                                @if($selectedOrderDetails->cancellation_reason)
                                Reason provided by you.
                                @else
                                No cancellation reason was recorded.
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif

                    <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3 inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
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
                                        <td class="px-4 py-2 font-medium text-gray-800 dark:text-slate-100">{{ $item->product->name ?? 'N/A'
                                            }}</td>
                                        <td class="px-4 py-2 text-gray-600 dark:text-slate-300">{{ $item->quantity }}</td>
                                        <td class="px-4 py-2">
                                            @if($item->original_price && $item->original_price > $item->price)
                                            <span class="text-red-600 dark:text-red-400 font-medium">₱{{ number_format($item->price, 2)
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
                                        <td class="px-4 py-2 text-gray-600 dark:text-slate-300 text-xs">
                                            @if($item->notes)
                                            <span class="italic">"{{ $item->notes }}"</span>
                                            @else
                                            <span class="text-gray-400 dark:text-slate-500">—</span>
                                            @endif
                                            @if($item->status === 'cancelled' && $item->cancellation_reason)
                                            <p class="text-red-600 dark:text-red-400 italic mt-0.5">Cancelled — {{
                                                $item->cancellation_reason }}</p>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                                Original Order
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Subtotal:
                                        </td>
                                        <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['original_subtotal'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):
                                        </td>
                                        <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['original_vat'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-1 text-right text-sm font-semibold text-gray-800 dark:text-slate-100">Original
                                            Total:</td>
                                        <td colspan="2" class="px-4 py-1 font-bold text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['original_total'], 2) }}</td>
                                    </tr>

                                    @if($b['amount_charged'] > 0)
                                    <tr>
                                        <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                            <div
                                                class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider inline-flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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
                                        <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">Completed
                                            Items:</td>
                                        <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['charged_subtotal'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):
                                        </td>
                                        <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['charged_vat'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-1 text-right text-sm font-semibold text-green-700 dark:text-green-300">Amount
                                            Charged:</td>
                                        <td colspan="2" class="px-4 py-1 font-bold text-green-600 dark:text-green-400">₱{{
                                            number_format($b['amount_charged'], 2) }}</td>
                                    </tr>
                                    @endif

                                    @if($b['amount_not_charged'] > 0)
                                    <tr>
                                        <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200 dark:border-slate-700">
                                            <div
                                                class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider inline-flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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
                                        <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):
                                        </td>
                                        <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['not_charged_vat'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-red-700 dark:text-red-300">
                                            Amount Not Charged:</td>
                                        <td colspan="2" class="px-4 py-1 font-bold text-red-600 dark:text-red-400">₱{{
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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
                                        <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600 dark:text-slate-300">VAT (12%):
                                        </td>
                                        <td colspan="2" class="px-4 py-1 font-medium text-gray-800 dark:text-slate-100">₱{{
                                            number_format($b['outstanding_vat'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-1 text-right text-sm font-semibold text-amber-700 dark:text-amber-300">Amount
                                            Outstanding:</td>
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
                            <p class="text-sm text-gray-700 dark:text-slate-300">
                                <span class="font-medium">Branch:</span> {{ $selectedOrderDetails->branch->name ?? 'N/A'
                                }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-slate-300">
                                <span class="font-medium">Pickup Time:</span> {{
                                \Carbon\Carbon::parse($selectedOrderDetails->pickup_time)->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex-shrink-0 flex justify-end">
                    <button @click="open = false; $wire.closeDetailsModal()"
                        class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-slate-100 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm font-medium">
                        Close
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ✅ Review Details Modal -->
    @if($showReviewDetailsModal && $selectedReviewOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeReviewDetailsModal"></div>

        <div
            class="relative z-10 w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-green-50 dark:bg-green-900/30 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-slate-100 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                            Your Review
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400">
                            Order #{{ $selectedReviewOrder->order_number }} •
                            {{ $selectedReviewOrder->shop->shop_name ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="closeReviewDetailsModal" class="text-gray-500 dark:text-slate-300 hover:text-gray-700 dark:hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                @if($selectedReviewOrder->serviceReview->moderation_status === 'removed')
                <div class="p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                        </path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-200">This review was removed by the Super Admin</p>
                        <p class="text-xs text-red-700 dark:text-red-300 mt-0.5">
                            It is no longer visible to other customers. If you believe this was a mistake, please
                            contact support.
                        </p>
                    </div>
                </div>
                @endif

                <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-slate-300">Service Quality</p>
                            <div class="flex items-center gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++) @if($i <=$selectedReviewOrder->serviceReview->rating)
                                    <svg class="w-6 h-6 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @endif
                                    @endfor
                                    <span class="text-sm text-gray-500 dark:text-slate-400 ml-2">({{
                                        $selectedReviewOrder->serviceReview->rating }}/5)</span>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-slate-500">{{
                            $selectedReviewOrder->serviceReview->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-slate-300 inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Employee Service
                            </p>
                            <div class="flex items-center gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++) @if($i <=$selectedReviewOrder->
                                    serviceReview->employee_rating)
                                    <svg class="w-6 h-6 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @endif
                                    @endfor
                                    <span class="text-sm text-gray-500 dark:text-slate-400 ml-2">({{
                                        $selectedReviewOrder->serviceReview->employee_rating }}/5)</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($selectedReviewOrder->serviceReview->review)
                <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Your Review
                    </p>
                    <p class="text-sm text-gray-600 dark:text-slate-300 italic">"{{ $selectedReviewOrder->serviceReview->review }}"</p>
                </div>
                @endif

                @php
                $productReviews = \App\Models\ProductReview::where('order_id', $selectedReviewOrder->id)
                ->with('product')
                ->get();
                @endphp
                @if($productReviews->count() > 0)
                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Product Reviews
                    </h4>
                    <div class="space-y-3">
                        @foreach($productReviews as $productReview)
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-800 dark:text-slate-100 text-sm">{{ $productReview->product->name ??
                                    'Product Unavailable' }}</p>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++) @if($i <=$productReview->rating)
                                        <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        @endif
                                        @endfor
                                </div>
                            </div>
                            @if($productReview->review)
                            <p class="text-sm text-gray-600 dark:text-slate-300 mt-1 italic">"{{ $productReview->review }}"</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex-shrink-0 flex justify-end">
                <button wire:click="closeReviewDetailsModal"
                    class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-slate-100 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ✅ Review Modal (with read-only mode) -->
    @if($showReviewModal && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-hidden p-4"
        style="overscroll-behavior: contain;">
        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-lg" wire:click="closeReviewModal"></div>
        <div
            class="relative z-10 flex w-full max-w-2xl max-h-[85vh] flex-col overflow-hidden text-left bg-white dark:bg-slate-800 rounded-2xl shadow-2xl">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-amber-50 dark:bg-slate-800 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-slate-100 inline-flex items-center gap-2">
                            @if($reviewReadOnly)
                            <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                            Your Review
                            @elseif($selectedOrder->serviceReview)
                            <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Review
                            @else
                            <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                            Leave Review
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400">
                            Order #{{ $selectedOrder->order_number }} •
                            @if($selectedOrder->shop)
                            {{ $selectedOrder->shop->shop_name }}
                            @else
                            <span class="text-gray-400 dark:text-slate-500">Shop Unavailable</span>
                            @endif
                        </p>
                    </div>
                    <button wire:click="closeReviewModal" class="text-gray-500 dark:text-slate-300 hover:text-gray-700 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="min-h-0 flex-1 px-6 py-4 space-y-6 overflow-y-auto">

                @if($reviewReadOnly)
                @php
                $status = $selectedOrder->serviceReview->moderation_status ?? 'visible';
                $editCount = $selectedOrder->serviceReview->edit_count ?? 0;
                $maxEdits = \App\Livewire\Customer\Orders::MAX_EDITS;
                @endphp

                @if($status === 'removed')
                <div class="p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                        </path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-200">This review was removed by the Super Admin</p>
                        <p class="text-xs text-red-700 dark:text-red-300 mt-0.5">
                            It is no longer visible to other customers and can no longer be edited.
                            If you believe this was a mistake, please contact support.
                        </p>
                    </div>
                </div>
                @elseif($status === 'pending_review')
                <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">This review is under review by the Super Admin
                        </p>
                        <p class="text-xs text-yellow-700 mt-0.5">
                            It has been flagged for moderation. You can view it, but it can't be edited until the
                            decision is final.
                        </p>
                    </div>
                </div>
                @elseif($editCount >= $maxEdits)
                <div class="p-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">You've reached the edit limit</p>
                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">
                            You've edited this review {{ $editCount }} times. You can view it, but no longer edit it.
                        </p>
                    </div>
                </div>
                @endif
                @endif

                <div>
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-slate-100 mb-3">Service Quality</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-slate-300 mb-1">How was your overall experience?</label>
                            <div class="flex gap-2" x-data="{ rating: @entangle('serviceRating') }">
                                @for($i = 1; $i <= 5; $i++) <button
                                    @click="{{ $reviewReadOnly ? '' : 'rating = ' . $i }}" {{ $reviewReadOnly
                                    ? 'disabled' : '' }} wire:key="service-star-{{ $i }}-{{ $selectedOrder->id }}"
                                    class="focus:outline-none transition {{ $reviewReadOnly ? 'cursor-default' : 'hover:scale-110' }}">
                                    <template x-if="rating >= {{ $i }}">
                                        <svg class="w-9 h-9 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                    </template>
                                    <template x-if="rating < {{ $i }}">
                                        <svg class="w-9 h-9 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                    </template>
                                    </button>
                                    @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-slate-300 mb-1">How was the employee's service?</label>
                            <div class="flex gap-2" x-data="{ rating: @entangle('employeeRating') }">
                                @for($i = 1; $i <= 5; $i++) <button
                                    @click="{{ $reviewReadOnly ? '' : 'rating = ' . $i }}" {{ $reviewReadOnly
                                    ? 'disabled' : '' }} wire:key="employee-star-{{ $i }}-{{ $selectedOrder->id }}"
                                    class="focus:outline-none transition {{ $reviewReadOnly ? 'cursor-default' : 'hover:scale-110' }}">
                                    <template x-if="rating >= {{ $i }}">
                                        <svg class="w-9 h-9 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                    </template>
                                    <template x-if="rating < {{ $i }}">
                                        <svg class="w-9 h-9 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                    </template>
                                    </button>
                                    @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-slate-300 mb-1">Additional comments (optional)</label>
                            <textarea wire:model="serviceReviewText" rows="2" @disabled($reviewReadOnly)
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-amber-500 focus:border-amber-500 @if($reviewReadOnly) bg-gray-50 dark:bg-slate-900 text-gray-600 dark:text-slate-300 @endif"
                                placeholder="Share your experience..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-slate-100 mb-3">Product Reviews (Optional)</h4>

                    @php
                    $completedItems = $selectedOrder->items->filter(function ($item) {
                    return $item->status === 'completed';
                    });
                    $noShowItems = $selectedOrder->items->filter(function ($item) {
                    return $item->status === 'no_show' || $item->status === 'cancelled';
                    });
                    @endphp

                    @if($noShowItems->count() > 0)
                    <div
                        class="mb-3 p-2 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg text-xs text-yellow-700 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        {{ $noShowItems->count() }} item(s) (No Show/Cancelled) are not eligible for review.
                    </div>
                    @endif

                    <div class="space-y-4">
                        @if($completedItems->count() > 0)
                        @foreach($completedItems as $item)
                        @php
                        $productId = $item->product_id;
                        $currentRating = $productRatings[$productId] ?? 0;
                        @endphp
                        <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-gray-100 dark:bg-slate-800 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($item->product && $item->product->image_url)
                                    <img src="{{ asset($item->product->image_url) }}"
                                        class="w-full h-full object-cover">
                                    @else
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-slate-100 text-sm">
                                        @if($item->product)
                                        {{ $item->product->name }}
                                        @else
                                        <span class="text-gray-400 dark:text-slate-500">Product Unavailable</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div>
                                    <div class="flex gap-1" x-data="{ rating: {{ $currentRating }} }"
                                        x-init="{{ $reviewReadOnly ? '' : '$watch(\'rating\', value => $wire.set(\'productRatings.' . $productId . '\', value))' }}">
                                        @for($i = 1; $i <= 5; $i++) <button
                                            @click="{{ $reviewReadOnly ? '' : 'rating = ' . $i }}" {{ $reviewReadOnly
                                            ? 'disabled' : '' }}
                                            wire:key="product-star-{{ $productId }}-{{ $i }}-{{ $selectedOrder->id }}"
                                            class="focus:outline-none transition {{ $reviewReadOnly ? 'cursor-default' : 'hover:scale-110' }}">
                                            <template x-if="rating >= {{ $i }}">
                                                <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                    </path>
                                                </svg>
                                            </template>
                                            <template x-if="rating < {{ $i }}">
                                                <svg class="w-5 h-5 text-gray-300 dark:text-slate-600" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                    </path>
                                                </svg>
                                            </template>
                                            </button>
                                            @endfor
                                    </div>
                                    <input type="text" wire:model="productReviews.{{ $productId }}"
                                        @disabled($reviewReadOnly) placeholder="Optional review..."
                                        class="w-full mt-1 px-2 py-1 text-xs border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-amber-500 focus:border-amber-500 @if($reviewReadOnly) bg-gray-50 dark:bg-slate-900 text-gray-600 dark:text-slate-300 @endif">
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-4 text-gray-500 dark:text-slate-400">
                            <p class="text-sm">No completed items to review.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex gap-3 flex-shrink-0">
                @if($reviewReadOnly)
                <button wire:click="closeReviewModal"
                    class="flex-1 px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-slate-100 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm font-medium">
                    Close
                </button>
                @else
                <button wire:click="closeReviewModal"
                    class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-slate-100 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm font-medium">
                    Skip
                </button>
                <button wire:click="submitReview"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    @if($selectedOrder->serviceReview)
                    Update Review
                    @else
                    Submit Review
                    @endif
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ✅ Cancel Reason Modal --}}
    <div x-data="{ open: false }" @open-cancel-modal.window="open = true;" @close-cancel-modal.window="open = false;"
        x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false"></div>

        <div class="relative z-10 w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
            x-show="open" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-red-50 dark:bg-red-900/30 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-red-800 dark:text-red-200 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span x-text="$wire.cancelTarget === 'order' ? 'Cancel Order' : 'Cancel Item'"></span>
                        </h3>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-0.5" x-text="$wire.cancelSummary"></p>
                    </div>
                    <button @click="open = false" type="button" class="text-gray-500 dark:text-slate-300 hover:text-gray-700 dark:hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Why are you cancelling? <span class="text-red-500 dark:text-red-400">*</span>
                    </p>

                    <div class="space-y-2">
                        @foreach(\App\Livewire\Customer\Orders::CANCEL_REASONS as $r)
                        <label
                            class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition text-sm {{ $cancelReason === $r ? 'border-amber-500 dark:border-amber-500 bg-amber-50 dark:bg-amber-900/30' : 'border-gray-200 dark:border-slate-700 hover:border-amber-300 dark:hover:border-amber-600' }}">
                            <input type="radio" wire:model.live="cancelReason" value="{{ $r }}"
                                class="text-amber-600 dark:text-amber-400 focus:ring-amber-500">
                            <span class="text-gray-800 dark:text-slate-100">{{ $r }}</span>
                        </label>
                        @endforeach

                        <label
                            class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition text-sm {{ $cancelReason === 'Other reason' ? 'border-amber-500 dark:border-amber-500 bg-amber-50 dark:bg-amber-900/30' : 'border-gray-200 dark:border-slate-700 hover:border-amber-300 dark:hover:border-amber-600' }}">
                            <input type="radio" wire:model.live="cancelReason" value="Other reason"
                                class="text-amber-600 dark:text-amber-400 focus:ring-amber-500">
                            <span class="text-gray-800 dark:text-slate-100">Other reason</span>
                        </label>
                    </div>
                    @error('cancelReason')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if($cancelReason === 'Other reason')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">
                        Please specify <span class="text-red-500 dark:text-red-400">*</span>
                    </label>
                    <textarea wire:model="cancelOtherText" rows="3" maxlength="100"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('cancelOtherText') border-red-500 @enderror"
                        placeholder="Tell us briefly why you're cancelling..."></textarea>
                    @error('cancelOtherText')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>

            <div class="px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex justify-end gap-2 flex-shrink-0">
                <button @click="open = false" type="button"
                    class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-slate-100 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm font-medium">
                    Keep
                </button>
                <button wire:click="submitCancellation" wire:loading.attr="disabled" wire:target="submitCancellation"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-sm disabled:opacity-50">
                    <span wire:loading.remove wire:target="submitCancellation">Confirm Cancellation</span>
                    <span wire:loading wire:target="submitCancellation">Cancelling...</span>
                </button>
            </div>
        </div>
    </div>
</div>
