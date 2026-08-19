<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('livewire.owner.branches.manage-cards') }}"
                        class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">📋 Order History</h1>
                        <p class="text-sm text-gray-500">
                            {{ $branch->name }} • {{ $orders->count() }} completed orders
                        </p>
                    </div>
                </div>
            </div>
            <a href="{{ route('livewire.owner.branches.manage-cards') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                ← Back to Branches
            </a>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Amount</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->items->count() }}</td>
                            <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($order->total_amount,
                                2) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td class="px-4 py-3">
                                <button wire:click="viewOrderDetails({{ $order->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <p>No completed orders for this branch yet.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Details Modal -->
    @if($showOrderDetails && $selectedOrder)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeOrderDetails">
            </div>

            <div
                class="relative z-10 inline-block w-full max-w-2xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle">

                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                📋 Order #{{ $selectedOrder->order_number }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $selectedOrder->branch->name }} • {{ $selectedOrder->customer->name ?? 'N/A' }}
                            </p>
                        </div>
                        <button wire:click="closeOrderDetails" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <!-- Order Info -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <p class="text-xs text-gray-500">Order Date</p>
                            <p class="text-sm font-medium text-gray-800">{{ $selectedOrder->created_at->format('M d, Y
                                h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Status</p>
                            <span
                                class="px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $selectedOrder->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $selectedOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $selectedOrder->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $selectedOrder->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $selectedOrder->status)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Payment Method</p>
                            <p class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ',
                                $selectedOrder->payment_method)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Payment Status</p>
                            <p class="text-sm font-medium text-gray-800">{{ ucfirst($selectedOrder->payment_status) }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">📦 Order Items</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Product</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Qty</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Price</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($selectedOrder->items as $item)
                                    <tr>
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ $item->product->name }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $item->quantity }}</td>
                                        <td class="px-4 py-2 text-gray-600">₱{{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-2 font-semibold text-green-600">₱{{
                                            number_format($item->quantity * $item->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-right font-semibold text-gray-800">Total:
                                        </td>
                                        <td class="px-4 py-2 font-bold text-green-600">₱{{
                                            number_format($selectedOrder->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    @if($selectedOrder->notes)
                    <div>
                        <p class="text-xs text-gray-500">Notes</p>
                        <p class="text-sm text-gray-700">{{ $selectedOrder->notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <button wire:click="closeOrderDetails"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>