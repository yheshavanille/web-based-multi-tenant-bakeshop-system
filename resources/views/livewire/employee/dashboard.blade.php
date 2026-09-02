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
    <!-- Order Manager Dashboard -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">📋 Recent Orders</h2>
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">📦 Products</h2>
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
    @endif
</div>