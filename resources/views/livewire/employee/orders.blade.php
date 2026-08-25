<div>
    <div class="max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    📋 Orders Dashboard
                </h1>

                <p class="text-sm text-gray-500">
                    Manage orders for
                    <span class="font-medium text-amber-600">
                        {{ $branch->name }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('livewire.employee.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
                <select wire:model.live="selectedStatus"
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm appearance-none bg-white bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat pr-10">
                    <option value="all">📋 All Orders</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="preparing">🔵 Preparing</option>
                    <option value="ready_for_pickup">✅ Ready</option>
                    <option value="completed">📦 Completed</option>
                    <option value="no_show">🚫 No Show</option>
                    <option value="cancelled">🚫 Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search by order #, customer name, or product..."
                    class="w-full pl-10 pr-10 h-10 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500">
                Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
                <span class="text-gray-400">({{ $orders->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Orders Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            @if($orders->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full divide-y divide-gray-200 text-sm">

                    <!-- Table Header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">
                                Order #
                            </th>

                            <th class="px-4 py-3 text-left font-medium text-gray-700">
                                Customer
                            </th>

                            <th class="px-4 py-3 text-left font-medium text-gray-700">
                                Items
                            </th>

                            <th class="px-4 py-3 text-left font-medium text-gray-700">
                                Total
                            </th>

                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">

                        @foreach($orders as $order)

                        <!-- Order Summary -->
                        <tr class="bg-gray-50">

                            <td class="px-4 py-3 font-medium text-gray-800">
                                #{{ $order->order_number }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $order->customer->name ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $order->items->count() }} items
                            </td>

                            <td class="px-4 py-3 font-medium text-amber-600">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                <button wire:click="openDetailsModal({{ $order->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">
                                    View Details
                                </button>
                            </td>

                        </tr>


                        <!-- Order Items -->
                        <tr>
                            <td colspan="5" class="px-4 py-3 bg-gray-50 border-t border-gray-200">

                                <div>

                                    <!-- Order Items Header -->
                                    <div class="flex items-center justify-between px-4 mb-4">

                                        <!-- Left: Order Items -->
                                        <span class="text-sm font-semibold text-gray-700">
                                            📦 Order Items
                                        </span>

                                        <!-- Right: Single Status Label -->
                                        <div class="w-[245px] flex justify-center">
                                            <span class="text-sm font-semibold text-gray-700">
                                                Status
                                            </span>
                                        </div>

                                    </div>


                                    <!-- Items -->
                                    <div class="space-y-2">

                                        @foreach($order->items as $item)

                                        <div
                                            class="flex items-center justify-between border-b border-gray-100 pb-2 last:border-0 px-4">

                                            <!-- Item Information -->
                                            <div class="min-w-0">

                                                <p class="text-sm font-medium text-gray-800">
                                                    {{ $item->product?->name ?? 'Product Unavailable' }}
                                                </p>

                                                <p class="text-xs text-gray-500">
                                                    Qty: {{ $item->quantity }}
                                                    x ₱{{ number_format($item->price, 2) }}

                                                    @if($item->pickup_time)
                                                    | 🕐
                                                    {{ \Carbon\Carbon::parse($item->pickup_time)->format('M d, h:i A')
                                                    }}
                                                    @endif
                                                </p>

                                            </div>


                                            <!-- Status Controls -->
                                            <div class="w-[245px] flex items-center justify-end gap-3">

                                                <!-- Status Badge -->
                                                <span
                                                    class="text-xs px-2.5 py-1 rounded-full whitespace-nowrap
                                                    {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $item->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $item->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $item->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                </span>

                                                <!-- ✅ Status Dropdown - HIDE for cancelled items -->
                                                @if($item->status !== 'cancelled' && $item->order->status !==
                                                'cancelled')
                                                <select
                                                    wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
                                                    class="px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                                    <option value="pending" {{ $item->status === 'pending' ? 'selected'
                                                        : '' }}>Pending</option>
                                                    <option value="preparing" {{ $item->status === 'preparing' ?
                                                        'selected' : '' }}>Preparing</option>
                                                    <option value="ready_for_pickup" {{ $item->status ===
                                                        'ready_for_pickup' ? 'selected' : '' }}>Ready</option>
                                                    <option value="completed" {{ $item->status === 'completed' ?
                                                        'selected' : '' }}>Completed</option>
                                                    <option value="no_show" {{ $item->status === 'no_show' ? 'selected'
                                                        : '' }}>No Show</option>
                                                    <option value="cancelled" {{ $item->status === 'cancelled' ?
                                                        'selected' : '' }}>Cancelled</option>
                                                </select>
                                                @else
                                                <span class="text-xs text-gray-400 font-medium">—</span>
                                                @endif

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

            @else

            <div class="text-center py-12 text-gray-500">
                @if(!empty($search))
                <p>No orders found matching "<span class="font-medium text-amber-600">{{ $search }}</span>"</p>
                <p class="text-xs text-gray-400">Try adjusting your search or status filter.</p>
                @else
                <p>No orders for this branch yet.</p>
                @endif
            </div>

            @endif

        </div>

    </div>

    <!-- ✅ Order Details Modal -->
    @if($showDetailsModal && $selectedOrderDetails)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm" wire:click="closeDetailsModal"></div>

        <div
            class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Order Details</h3>
                        <p class="text-sm text-gray-500">
                            #{{ $selectedOrderDetails->order_number }} •
                            {{ $selectedOrderDetails->branch->name ?? 'N/A' }} •
                            {{ $selectedOrderDetails->customer->name ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <!-- Order Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order Date</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedOrderDetails->created_at->format('M d,
                            Y h:i A') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrderDetails->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $selectedOrderDetails->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $selectedOrderDetails->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $selectedOrderDetails->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $selectedOrderDetails->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $selectedOrderDetails->status)) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ',
                            $selectedOrderDetails->payment_method)) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Payment Status</p>
                        <span
                            class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrderDetails->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($selectedOrderDetails->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Product</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Qty</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Price</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Subtotal</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($selectedOrderDetails->items as $item)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $item->product->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-gray-600">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-2 text-gray-600">₱{{ number_format($item->price *
                                        $item->quantity, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $item->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $item->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-right font-semibold text-gray-800">Total:</td>
                                    <td colspan="2" class="px-4 py-2 font-bold text-amber-600">₱{{
                                        number_format($selectedOrderDetails->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Pickup Details -->
                @if($selectedOrderDetails->pickup_time)
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📍 Pickup Details</h4>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Branch:</span> {{ $selectedOrderDetails->branch->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Pickup Time:</span> {{
                            \Carbon\Carbon::parse($selectedOrderDetails->pickup_time)->format('M d, Y h:i A') }}
                        </p>
                        @if($selectedOrderDetails->notes)
                        <p class="text-sm text-gray-700 mt-1">
                            <span class="font-medium">Notes:</span> {{ $selectedOrderDetails->notes }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeDetailsModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>