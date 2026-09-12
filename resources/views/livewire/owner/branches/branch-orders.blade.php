<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('livewire.owner.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">📋 All Orders</h1>
                        <p class="text-sm text-gray-500">
                            {{ $branch->name }} • {{ $orders->count() }} total orders
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <!-- Search Bar -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search by order #, customer name, or product..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                    style="position: absolute;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>

            <!-- Status Filter -->
            <select wire:model.live="selectedStatus"
                class="px-4 py-2 pr-10 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm appearance-none bg-white bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat min-w-[150px]">
                <option value="all">📋 All Status</option>
                <option value="pending">⏳ Pending</option>
                <option value="preparing">🔵 Preparing</option>
                <option value="ready_for_pickup">✅ Ready</option>
                <option value="completed">📦 Completed</option>
                <option value="partially_completed">🟡 Partially Completed</option>
                <option value="no_show">🚫 No Show</option>
                <option value="cancelled">🚫 Cancelled</option>
            </select>
        </div>

        @if(!empty($search))
        <p class="mt-1 text-xs text-gray-500">
            Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
            <span class="text-gray-400">({{ $orders->count() }} found)</span>
        </p>
        @endif

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
                            <td class="px-4 py-3 text-gray-600">{{ $order->item_count }} items</td>
                            <td class="px-4 py-3 font-semibold text-green-600">₱{{
                                number_format($order->adjusted_total
                                ?? $order->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="viewOrderDetails({{ $order->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                @if(!empty($search))
                <p>No orders found matching "<span class="font-medium text-amber-600">{{ $search }}</span>"</p>
                <p class="text-xs text-gray-400">Try adjusting your search.</p>
                @else
                <p>No orders for this branch yet.</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- ✅ Order Details Modal - WITH BREAKDOWN SUMMARY -->
    @if($showOrderDetails && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
        style="overscroll-behavior: contain;">
        <!-- Blurry Background -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeOrderDetails"></div>

        <div class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 92vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">📋 Order Details</h3>
                        <p class="text-sm text-gray-500">
                            #{{ $selectedOrder->order_number }} •
                            {{ $selectedOrder->branch->name ?? 'N/A' }} •
                            {{ $selectedOrder->customer->name ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="closeOrderDetails" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <!-- Order Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order Date</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedOrder->created_at->format('M d, Y
                            h:i
                            A') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $selectedOrder->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $selectedOrder->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $selectedOrder->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $selectedOrder->status === 'partially_completed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $selectedOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $selectedOrder->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $selectedOrder->status)) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedOrder->payment_method_label }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Payment Status</p>
                        <span class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrder->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $selectedOrder->payment_status === 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $selectedOrder->payment_status === 'refunded' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $selectedOrder->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ ucfirst($selectedOrder->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">📦 Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Product</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Qty</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Price</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Original</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Subtotal</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($selectedOrder->items as $item)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $item->product->name ?? 'N/A'
                                        }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">
                                        @if($item->original_price && $item->original_price > $item->price)
                                        <span class="text-red-600 font-medium">₱{{ number_format($item->price, 2)
                                            }}</span>
                                        @else
                                        <span class="text-gray-600">₱{{ number_format($item->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($item->original_price && $item->original_price > $item->price)
                                        <span class="text-gray-400 line-through">₱{{
                                            number_format($item->original_price, 2) }}</span>
                                        @else
                                        <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">₱{{ number_format($item->price *
                                        $item->quantity, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $item->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $item->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $item->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                @php $b = $this->getBreakdown(); @endphp

                                {{-- 📦 ORIGINAL ORDER --}}
                                <tr>
                                    <td colspan="6" class="px-4 pt-3 pb-1">
                                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">📦
                                            Original Order</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">Subtotal:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['original_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['original_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm font-semibold text-gray-800">
                                        Original Total:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-gray-800">₱{{
                                        number_format($b['original_total'], 2) }}</td>
                                </tr>

                                {{-- ✅ CHARGED TO CUSTOMER --}}
                                @if($b['amount_charged'] > 0)
                                <tr>
                                    <td colspan="6" class="px-4 pt-3 pb-1 border-t border-gray-200">
                                        <div class="text-xs font-semibold text-green-600 uppercase tracking-wider">✅
                                            Charged to Customer</div>
                                    </td>
                                </tr>
                                @foreach($b['charged_items'] as $item)
                                <tr>
                                    <td colspan="4" class="px-4 py-0.5 text-right text-xs text-gray-500">
                                        • {{ $item['name'] }} ({{ $item['quantity'] }}x)
                                    </td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">Completed Items:
                                    </td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm font-semibold text-green-700">
                                        Amount Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-green-600">₱{{
                                        number_format($b['amount_charged'], 2) }}</td>
                                </tr>
                                @endif

                                {{-- ❌ NOT CHARGED --}}
                                @if($b['amount_not_charged'] > 0)
                                <tr>
                                    <td colspan="6" class="px-4 pt-3 pb-1 border-t border-gray-200">
                                        <div class="text-xs font-semibold text-red-600 uppercase tracking-wider">❌ Not
                                            Charged</div>
                                    </td>
                                </tr>
                                @foreach($b['not_charged_items'] as $item)
                                <tr>
                                    <td colspan="4" class="px-4 py-0.5 text-right text-xs text-gray-500">
                                        • {{ $item['name'] }} ({{ $item['quantity'] }}x)
                                    </td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">No Show /
                                        Cancelled:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['not_charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['not_charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm font-semibold text-red-700">
                                        Amount Not Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-red-600">₱{{
                                        number_format($b['amount_not_charged'], 2) }}</td>
                                </tr>
                                @endif

                                {{-- ⏳ OUTSTANDING --}}
                                @if($b['amount_outstanding'] > 0)
                                <tr>
                                    <td colspan="6" class="px-4 pt-3 pb-1 border-t border-gray-200">
                                        <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider">⏳
                                            Outstanding</div>
                                    </td>
                                </tr>
                                @foreach($b['outstanding_items'] as $item)
                                <tr>
                                    <td colspan="4" class="px-4 py-0.5 text-right text-xs text-gray-500">
                                        • {{ $item['name'] }} ({{ $item['quantity'] }}x)
                                    </td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">Pending +
                                        Preparing + Ready:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['outstanding_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['outstanding_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-1 text-right text-sm font-semibold text-amber-700">
                                        Amount Outstanding:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-amber-600">₱{{
                                        number_format($b['amount_outstanding'], 2) }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Pickup Details -->
                @if($selectedOrder->pickup_time)
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📍 Pickup Details</h4>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Branch:</span> {{ $selectedOrder->branch->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Pickup Time:</span> {{
                            \Carbon\Carbon::parse($selectedOrder->pickup_time)->format('M d, Y h:i A') }}
                        </p>
                        @if($selectedOrder->notes)
                        <p class="text-sm text-gray-700 mt-1">
                            <span class="font-medium">Notes:</span> {{ $selectedOrder->notes }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeOrderDetails"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>