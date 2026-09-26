<div>
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-sm border border-amber-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center text-3xl shadow-md">
                👋
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600">
                    {{ $role === 'order_manager' ? '📋 Order Manager' : '📦 Inventory Manager' }}
                    <span class="text-gray-400">|</span>
                    📍 {{ $branch->name }}
                </p>
            </div>
        </div>
    </div>

    @if($role === 'order_manager')

    <!-- Recent Pending Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-gray-800">Recent Pending Orders</h2>
                <span class="text-sm text-gray-500">Last 5 pending orders</span>
            </div>
            <a href="{{ route('livewire.employee.orders', ['status' => 'pending']) }}"
                class="text-sm text-amber-600 hover:text-amber-800 font-medium">
                View All →
            </a>
        </div>

        @if($recentPendingOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Placed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($recentPendingOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $order->item_count }} items
                            @if($order->pending_count > 0)
                            <span class="text-xs text-yellow-600 font-medium">({{ $order->pending_count }}
                                pending)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-amber-600">
                            ₱{{ number_format($order->display_total ?? $order->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <span class="text-3xl block mb-2">✅</span>
            <p class="text-sm">No pending orders right now.</p>
        </div>
        @endif
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
            <a href="{{ route('livewire.employee.orders') }}"
                class="text-sm text-amber-600 hover:text-amber-800 font-medium">
                View All →
            </a>
        </div>

        @if($orders->count() > 0)
        <div class="space-y-3">
            @foreach($orders as $order)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                    <p class="font-medium text-gray-800">#{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-500">{{ $order->items->count() }} items</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No orders for this branch yet.</p>
        </div>
        @endif
    </div>

    @elseif($role === 'inventory_manager')
    <!-- Inventory Manager Dashboard -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-sm text-gray-500">📦 Total Products</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-sm text-gray-500">⚠️ Low Stock (1-5)</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $lowStockCount }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-sm text-gray-500">🔴 Out of Stock (0)</p>
            <p class="text-2xl font-bold text-red-600">{{ $outOfStockCount }}</p>
        </div>
    </div>

    <!-- Products List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Products</h2>
        </div>
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Price</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Stock</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-600">₱{{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $product->stock > 5 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $product->stock <= 5 && $product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $product->stock <= 0 ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($product->stock <= 0) <span class="text-xs text-red-600 font-medium">🚫 Out of
                                Stock</span>
                                @elseif($product->stock <= 5) <span class="text-xs text-yellow-600 font-medium">⚠️ Low
                                    Stock</span>
                                    @else
                                    <span class="text-xs text-green-600 font-medium">✅ In Stock</span>
                                    @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No products for this branch yet.</p>
        </div>
        @endif
    </div>

    <!-- Recent Stock Updates -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-gray-800">Recent Stock Updates</h2>
                <span class="text-sm text-gray-500">Last 10 movements</span>
            </div>
            <a href="{{ route('livewire.employee.stock-history') }}"
                class="text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                View All →
            </a>
        </div>

        @if($stockHistories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-700">Old</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-700">Change</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-700">After</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Reason</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">By</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($stockHistories as $row)
                    @php
                    $typeLabels = [
                    'stock_in' => ['label' => 'Stock In', 'color' => 'green'],
                    'stock_out' => ['label' => 'Stock Out', 'color' => 'red'],
                    'adjustment' => ['label' => 'Adjustment', 'color' => 'yellow'],
                    'out' => ['label' => 'Customer Order', 'color' => 'blue'],
                    'cancelled' => ['label' => 'Order Cancelled', 'color' => 'orange'],
                    'no_show' => ['label' => 'Not Picked Up', 'color' => 'orange'],
                    ];
                    $info = $typeLabels[$row->type] ?? ['label' => ucfirst($row->type), 'color' => 'gray'];
                    $badgeClasses = match ($info['color']) {
                    'green' => 'bg-green-100 text-green-800',
                    'red' => 'bg-red-100 text-red-800',
                    'yellow' => 'bg-yellow-100 text-yellow-800',
                    'blue' => 'bg-blue-100 text-blue-800',
                    'orange' => 'bg-orange-100 text-orange-800',
                    default => 'bg-gray-100 text-gray-800',
                    };
                    $qty = (int) $row->quantity;

                    // Clean up order row reasons
                    $reason = $row->notes ?? '';
                    if ($row->kind === 'order') {
                    if (preg_match('/Order #(ORD-[A-Z0-9]+)/', $reason, $m)) {
                    $orderNo = $m[1];
                    } else {
                    $orderNo = null;
                    }
                    $reason = match ($row->type) {
                    'out' => $orderNo ? "Customer order — {$orderNo}" : 'Customer order',
                    'cancelled' => $orderNo ? "Order {$orderNo} was cancelled" : 'Order cancelled',
                    'no_show' => $orderNo ? "Order {$orderNo} was not picked up" : 'Order not picked up',
                    default => $reason,
                    };
                    }
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap {{ $badgeClasses }}">
                                {{ $info['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $row->product->name ?? 'Deleted product' }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-500">{{ $row->old_stock }}</td>
                        <td class="px-4 py-3 text-right font-semibold
                            {{ $qty > 0 ? 'text-green-600' : ($qty < 0 ? 'text-red-600' : 'text-gray-500') }}">
                            {{ $qty > 0 ? '+' : '' }}{{ $qty }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800">{{ $row->new_stock }}</td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate" title="{{ $reason }}">
                            {{ $reason ?: '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $row->user->name ?? 'System' }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                            {{ $row->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No stock movements yet.</p>
        </div>
        @endif
    </div>
    @endif
</div>