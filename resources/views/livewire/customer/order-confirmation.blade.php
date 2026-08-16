<div>
    <div class="max-w-3xl mx-auto">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🎉</div>
            <h1 class="text-3xl font-bold text-gray-800">Order Placed Successfully!</h1>
            <p class="text-gray-600 mt-2">Thank you for your order. Your bakeshop will prepare it fresh for you.</p>
        </div>

        <!-- Order Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Order Number</p>
                    <p class="font-semibold text-gray-800">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Shop</p>
                    <p class="font-medium text-gray-800">{{ $order->shop->shop_name }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items with Branch Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                    <div>
                        <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                        <p class="text-sm text-gray-500">₱{{ number_format($item->price, 2) }} x {{ $item->quantity }}
                        </p>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="text-xs text-gray-400">📍 {{ $item->branch->name ?? 'No branch' }}</span>
                            @if($item->pickup_time)
                            <span class="text-xs text-gray-400">🕐 {{
                                \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i A') }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="font-medium text-amber-600">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-4">
                <span class="text-lg font-semibold text-gray-800">Total</span>
                <span class="text-2xl font-bold text-amber-600">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Pickup Details Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 Pickup Details</h3>
            <div class="space-y-2">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                    <span class="text-gray-600">{{ $item->product->name }}</span>
                    <div class="text-right">
                        <span class="text-gray-700 block">{{ $item->branch->name ?? 'N/A' }}</span>
                        @if($item->pickup_time)
                        <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($item->pickup_time)->format('M d,
                            h:i A') }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <p class="text-sm text-gray-500 mt-3">
                <span class="font-medium">Shop:</span> {{ $order->shop->shop_name }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                ← Back to Dashboard
            </a>
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="px-6 py-3 border border-amber-600 text-amber-600 rounded-lg hover:bg-amber-50 transition">
                Continue Shopping →
            </a>
        </div>
    </div>
</div>