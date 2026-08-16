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
                    <span class="font-medium text-blue-600">
                        {{ $branch->name }}
                    </span>
                </p>
            </div>

            <div>
                <select wire:model.live="selectedStatus"
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all">📋 All Orders</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="preparing">🔵 Preparing</option>
                    <option value="ready_for_pickup">✅ Ready</option>
                    <option value="completed">📦 Completed</option>
                    <option value="no_show">🚫 No Show</option>
                </select>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
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

                            <td class="px-4 py-3"></td>

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
                                                    {{ $item->product->name }}
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
                                                    {{ $item->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                </span>


                                                <!-- Status Dropdown -->
                                                <select
                                                    wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
                                                    class="px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">

                                                    <option value="pending" {{ $item->status === 'pending' ? 'selected'
                                                        : '' }}>
                                                        Pending
                                                    </option>

                                                    <option value="preparing" {{ $item->status === 'preparing' ?
                                                        'selected' : '' }}>
                                                        Preparing
                                                    </option>

                                                    <option value="ready_for_pickup" {{ $item->status ===
                                                        'ready_for_pickup' ? 'selected' : '' }}>
                                                        Ready
                                                    </option>

                                                    <option value="completed" {{ $item->status === 'completed' ?
                                                        'selected' : '' }}>
                                                        Completed
                                                    </option>

                                                    <option value="no_show" {{ $item->status === 'no_show' ? 'selected'
                                                        : '' }}>
                                                        No Show
                                                    </option>

                                                </select>

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
                <p>No orders for this branch yet.</p>
            </div>

            @endif

        </div>

    </div>
</div>