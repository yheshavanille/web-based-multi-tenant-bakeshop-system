<div>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📋 My Orders</h1>
                <p class="text-sm text-gray-500">Track your order status</p>
            </div>
            <div>
                <select wire:model.live="selectedStatus"
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="all">📋 All Orders</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="preparing">🔵 Preparing</option>
                    <option value="ready_for_pickup">✅ Ready</option>
                    <option value="completed">📦 Completed</option>
                    <option value="cancelled">🚫 Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Shop</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Total</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Pickup Time</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($orders as $order)
                        <!-- Order Row -->
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($order->shop)
                                {{ $order->shop->shop_name }}
                                @else
                                <span class="text-gray-400">Shop Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($order->branch)
                                {{ $order->branch->name }}
                                @else
                                <span class="text-gray-400">Branch Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->items->count() }} items</td>
                            <td class="px-4 py-3 font-medium text-amber-600">₱{{ number_format($order->total_amount, 2)
                                }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($order->status === 'pending')
                                <button wire:click="cancelOrder({{ $order->id }})"
                                    onclick="confirm('Cancel entire order?') || event.stopImmediatePropagation()"
                                    class="text-xs text-red-600 hover:text-red-800 font-medium transition">
                                    Cancel Order
                                </button>
                                @elseif($order->status === 'completed' && !$order->serviceReview)
                                <button wire:click="openReviewModal({{ $order->id }})"
                                    class="text-xs text-amber-600 hover:text-amber-800 font-medium transition">
                                    ⭐ Leave Review
                                </button>
                                @elseif($order->status === 'completed' && $order->serviceReview)
                                <span class="text-xs text-green-600">✅ Reviewed</span>
                                @else
                                <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Order Items Row -->
                        <tr>
                            <td colspan="7" class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                <div class="flex flex-col space-y-2">
                                    <!-- Header -->
                                    <div class="flex justify-between items-center px-4">
                                        <span class="text-sm font-bold text-gray-800">Order Items</span>
                                        <span class="text-sm font-bold text-gray-800">Status</span>
                                    </div>
                                    <!-- Items -->
                                    @foreach($order->items as $item)
                                    <div
                                        class="flex justify-between items-center border-b border-gray-200 pb-2 last:border-0 px-4">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">
                                                @if($item->product)
                                                {{ $item->product->name }}
                                                @else
                                                <span class="text-gray-400">Product Unavailable</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Qty: {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}
                                                @if($item->pickup_time)
                                                | 🕐 {{ \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i A')
                                                }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs px-2.5 py-1 rounded-full
                                                {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $item->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $item->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $item->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                            </span>
                                            <!-- ✅ Cancel Individual Item Button -->
                                            @if($item->status === 'pending')
                                            <button wire:click="cancelItem({{ $item->id }})"
                                                onclick="confirm('Cancel this item?') || event.stopImmediatePropagation()"
                                                class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                                                ✕
                                            </button>
                                            @endif
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
            @else
            <div class="text-center py-12 text-gray-500">
                <p>No orders yet.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- ✅ Review Modal -->
    @if($showReviewModal && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-hidden p-4"
        style="overscroll-behavior: contain;">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75" wire:click="closeReviewModal"></div>
        <div
            class="relative z-10 flex w-full max-w-2xl max-h-[85vh] flex-col overflow-hidden text-left bg-white rounded-2xl shadow-2xl">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">✏️ Leave Review</h3>
                        <p class="text-sm text-gray-500">
                            Order #{{ $selectedOrder->order_number }} •
                            @if($selectedOrder->shop)
                            {{ $selectedOrder->shop->shop_name }}
                            @else
                            <span class="text-gray-400">Shop Unavailable</span>
                            @endif
                        </p>
                    </div>
                    <button wire:click="closeReviewModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="min-h-0 flex-1 px-6 py-4 space-y-6 overflow-y-auto">
                <!-- Service Quality -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">🌟 Service Quality</h4>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">How was your overall experience?</label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++) <button wire:click="setRating('service', {{ $i }})"
                                    class="text-3xl transition hover:scale-110 focus:outline-none">
                                    {{ $i <= $serviceRating ? '⭐' : '☆' }} </button>
                                        @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">How was the employee's service?</label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++) <button wire:click="setRating('employee', {{ $i }})"
                                    class="text-3xl transition hover:scale-110 focus:outline-none">
                                    {{ $i <= $employeeRating ? '⭐' : '☆' }} </button>
                                        @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Additional comments (optional)</label>
                            <textarea wire:model="serviceReviewText" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                placeholder="Share your experience..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Product Reviews -->
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">📦 Product Reviews (Optional)</h4>
                    <p class="text-xs text-gray-500 mb-3">Rate each product you ordered</p>

                    <div class="space-y-4">
                        @foreach($selectedOrder->items as $item)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl overflow-hidden">
                                    @if($item->product && $item->product->image_url)
                                    <img src="{{ asset($item->product->image_url) }}"
                                        class="w-full h-full object-cover">
                                    @else
                                    🍰
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">
                                        @if($item->product)
                                        {{ $item->product->name }}
                                        @else
                                        <span class="text-gray-400">Product Unavailable</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div>
                                    <div class="flex gap-1">
                                        @for($i = 1; $i <= 5; $i++) <button
                                            wire:click="setProductRating({{ $item->product_id }}, {{ $i }})"
                                            class="text-xl transition hover:scale-110 focus:outline-none">
                                            {{ $i <= ($productRatings[$item->product_id] ?? 0) ? '⭐' : '☆' }}
                                                </button>
                                                @endfor
                                    </div>
                                    <input type="text" wire:model="productReviews.{{ $item->product_id }}"
                                        placeholder="Optional review..."
                                        class="w-full mt-1 px-2 py-1 text-xs border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex gap-3 flex-shrink-0">
                <button wire:click="closeReviewModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Skip
                </button>
                <button wire:click="submitReview"
                    class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    Submit Review ⭐
                </button>
            </div>
        </div>
    </div>
    @endif
</div>