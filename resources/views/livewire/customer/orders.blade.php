<div>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📋 My Orders</h1>
            <p class="text-sm text-gray-500">Track your order status</p>
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
                            <td class="px-4 py-3 text-gray-600">{{ $order->shop->shop_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->branch->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->items->count() }} items</td>
                            <td class="px-4 py-3 font-medium text-amber-600">₱{{ number_format($order->total_amount, 2)
                                }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($order->status === 'pending')
                                <button wire:click="cancelOrder({{ $order->id }})"
                                    onclick="confirm('Are you sure you want to cancel this order?') || event.stopImmediatePropagation()"
                                    class="text-xs text-red-600 hover:text-red-800 font-medium transition">
                                    Cancel
                                </button>
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
                                            <p class="text-sm font-bold text-gray-800">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500">
                                                Qty: {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}
                                                @if($item->pickup_time)
                                                | 🕐 {{ \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i A')
                                                }}
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-xs px-2.5 py-1 rounded-full
                                                {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $item->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $item->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $item->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
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
            @else
            <div class="text-center py-12 text-gray-500">
                <p>No orders yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>