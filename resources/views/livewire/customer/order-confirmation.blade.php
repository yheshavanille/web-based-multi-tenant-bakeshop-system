<div>
    <div class="max-w-4xl mx-auto">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🎉</div>
            <h1 class="text-3xl font-bold text-gray-800">Order Placed Successfully!</h1>
            <p class="text-gray-600 mt-2">
                @if($orders->count() > 1)
                {{ $orders->count() }} orders placed successfully!
                @else
                Thank you for your order. Your bakeshop will prepare it fresh for you.
                @endif
            </p>
        </div>

        <!-- Orders Loop -->
        @foreach($orders as $index => $order)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 px-6 py-3 border-b border-gray-200">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <span class="font-semibold text-gray-800">Order #{{ $order->order_number }}</span>
                        @if($orders->count() > 1)
                        <span class="text-xs text-gray-500 ml-2">({{ $index + 1 }} of {{ $orders->count() }})</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-gray-500">Status:</span>
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Info -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="font-medium text-gray-800 text-sm">{{ $order->branch->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="font-medium text-gray-800 text-sm">{{ ucfirst(str_replace('_', ' ',
                            $order->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Order Date</p>
                        <p class="font-medium text-gray-800 text-sm">{{ $order->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Items</p>
                        <p class="font-medium text-gray-800 text-sm">{{ $order->items->count() }} items</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-start border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-500">₱{{ number_format($item->price, 2) }} x {{ $item->quantity
                                }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400">📍 {{ $item->branch->name ?? 'No branch' }}</span>
                                @if($item->pickup_time)
                                <span class="text-xs text-gray-400">🕐 {{
                                    \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i A') }}</span>
                                @endif
                            </div>
                        </div>
                        <p class="font-medium text-amber-600">₱{{ number_format($item->price * $item->quantity, 2) }}
                        </p>
                    </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="pt-3 border-t border-gray-200 mt-3">
                    <div class="space-y-1 text-right">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700">₱{{ number_format($order->subtotal ?? $order->total_amount, 2)
                                }}</span>
                        </div>
                        @if($order->tax_amount)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">VAT (12%)</span>
                            <span class="text-gray-700">₱{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        <div
                            class="flex justify-between items-center text-base font-bold pt-1 border-t border-gray-200">
                            <span class="text-gray-800">Order Total</span>
                            <span class="text-amber-600">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Combined Grand Total -->
        @if($orders->count() > 1)
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl p-6 text-white mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div>
                    <p class="text-sm text-amber-100">Combined Order Summary</p>
                    <p class="text-lg font-bold">{{ $orders->count() }} orders total</p>
                </div>
                <div class="text-right mt-2 sm:mt-0">
                    <p class="text-sm text-amber-100">Total Amount</p>
                    <p class="text-2xl font-bold">₱{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
            <div class="flex justify-between text-sm mt-3 pt-3 border-t border-amber-400/30">
                <span>Subtotal: ₱{{ number_format($totalSubtotal, 2) }}</span>
                <span>VAT: ₱{{ number_format($totalTax, 2) }}</span>
            </div>
        </div>
        @endif

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
            <a href="{{ route('livewire.customer.orders') }}"
                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                View All Orders →
            </a>
        </div>
    </div>
</div>