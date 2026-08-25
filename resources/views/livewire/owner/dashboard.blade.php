<div>
    <!-- Welcome Section with Shop Image -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            @php
            $shop = auth()->user()->shop;
            @endphp
            <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                @if($shop && $shop->shop_image)
                <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                    class="w-full h-full object-cover">
                @else
                <div class="w-full h-full bg-amber-100 flex items-center justify-center text-2xl">
                    🏪
                </div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-500">Manage your bakeshop and track performance.</p>
                @if($shopRatingCount > 0)
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-amber-500 text-sm">⭐</span>
                    <span class="font-semibold text-gray-800 text-sm">{{ number_format($shopRating, 1) }}</span>
                    <span class="text-sm text-gray-500">({{ $shopRatingCount }} reviews)</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SALES OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div
            class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Sales</p>
                <span class="text-2xl">💰</span>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">₱{{ number_format($totalSales, 2) }}</p>
            <p class="text-xs text-green-600 mt-1">From completed orders</p>
        </div>

        <div
            class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Orders</p>
                <span class="text-2xl">📋</span>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalOrders }}</p>
            <p class="text-xs text-blue-600 mt-1">Completed orders</p>
        </div>

        <div
            class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Products</p>
                <span class="text-2xl">📦</span>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalProducts }}</p>
            <p class="text-xs text-amber-600 mt-1">All branches</p>
        </div>

        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border border-purple-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Employees</p>
                <span class="text-2xl">👥</span>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $employeesCount }}</p>
            <p class="text-xs text-purple-600 mt-1">Active employees</p>
        </div>
    </div>

    <!-- Recent Order Updates -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">📋</span>
                <h2 class="text-lg font-semibold text-gray-800">Recent Order Updates</h2>
                <span class="text-sm text-gray-500">Last 10 orders</span>
            </div>
            <a href="{{ route('livewire.owner.branches.branch-orders', ['branchId' => $branches->first()?->id ?? 0]) }}"
                class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All →
            </a>
        </div>

        @if($recentOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Updated</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->branch?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->item_count }} items</td>
                        <td class="px-4 py-3 font-medium text-amber-600">₱{{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium text-gray-700">{{ $order->status_summary }}</span>
                            @if($order->cancelled_count > 0)
                            <span class="text-xs text-red-500">⚠️</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $order->updated_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="viewOrderDetails({{ $order->id }})"
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">
                                View Details
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No orders yet.</p>
        </div>
        @endif
    </div>

    <!-- Best Selling Products -->
    @if($bestSellers->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-xl">🏆</span>
            <h2 class="text-lg font-semibold text-gray-800">Best Selling Products</h2>
            <span class="text-xs text-gray-500 ml-auto">Top 5 products</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">#</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total Sold</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($bestSellers as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <div class="flex items-center gap-3">
                                @if($item->product && $item->product->image_url)
                                <img src="{{ asset($item->product->image_url) }}"
                                    class="w-8 h-8 rounded-lg object-cover">
                                @else
                                <span class="text-lg">🍰</span>
                                @endif
                                {{ $item->product?->name ?? 'Product Unavailable' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->total_sold }}</td>
                        <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($item->total_revenue, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Branch Performance -->
    @if(count($branchPerformance) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">📊 Branch Performance</h2>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="text-sm text-amber-600 hover:text-amber-700">
                Manage Branches →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total Sales</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total Orders</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($branchPerformance as $branch)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $branch['name'] }}</td>
                        <td class="px-4 py-3 text-green-600 font-medium">₱{{ number_format($branch['sales'], 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $branch['orders'] }}</td>
                        <td class="px-4 py-3">
                            @if($branch['rating_count'] > 0)
                            <span class="text-amber-500">⭐</span>
                            <span class="text-gray-700">{{ number_format($branch['rating'], 1) }}</span>
                            <span class="text-xs text-gray-400">({{ $branch['rating_count'] }})</span>
                            @else
                            <span class="text-xs text-gray-400">No reviews</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Reviews -->
    @if($recentReviews->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">📝</span>
                <h2 class="text-lg font-semibold text-gray-800">Recent Reviews</h2>
            </div>
            <a href="{{ route('livewire.owner.reviews-history') }}"
                class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Rating</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Review</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($recentReviews as $review)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $review->customer?->name ?? 'Anonymous' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $review->branch?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-amber-500 text-sm">{{ str_repeat('⭐', $review->rating) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $review->review ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $review->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('livewire.owner.branches.manage-branches') }}"
            class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center hover:bg-blue-100 transition">
            <div class="text-2xl mb-1">📍</div>
            <p class="text-sm font-medium text-blue-700">Manage Branches</p>
        </a>
        <a href="{{ route('livewire.owner.employees.manage') }}"
            class="bg-green-50 border border-green-200 rounded-xl p-4 text-center hover:bg-green-100 transition">
            <div class="text-2xl mb-1">👥</div>
            <p class="text-sm font-medium text-green-700">Manage Employees</p>
        </a>
        <a href="{{ route('livewire.owner.shop.edit-shop') }}"
            class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center hover:bg-purple-100 transition">
            <div class="text-2xl mb-1">⚙️</div>
            <p class="text-sm font-medium text-purple-700">Shop Settings</p>
        </a>
    </div>

    <!-- Your Branches -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">📍 Your Branches</h2>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="text-sm text-amber-600 hover:text-amber-700">
                View All →
            </a>
        </div>
        @if($branches->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($branches as $branch)
            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $branch->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $branch->address }}</p>
                    </div>
                    <span
                        class="px-2 py-1 text-xs rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    📦 {{ $branch->products_count ?? 0 }} products
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 text-gray-500">
            <p>No branches yet.</p>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}" class="text-amber-600 hover:underline">
                Create your first branch →
            </a>
        </div>
        @endif
    </div>

    <!-- Order Details Modal -->
    @if($showOrderModal && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeOrderModal"></div>
        <div class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Order Details</h3>
                        <p class="text-sm text-gray-500">
                            #{{ $selectedOrder->order_number }} •
                            {{ $selectedOrder->branch?->name ?? 'N/A' }} •
                            {{ $selectedOrder->customer?->name ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="closeOrderModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Order Date</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedOrder->created_at->format('M d, Y h:i
                            A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $selectedOrder->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $selectedOrder->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $selectedOrder->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $selectedOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
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
                        <span
                            class="text-sm font-medium px-2 py-0.5 rounded-full
                            {{ $selectedOrder->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($selectedOrder->payment_status) }}
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
                                @foreach($selectedOrder->items as $item)
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
                                        number_format($selectedOrder->adjusted_total ?? $selectedOrder->total_amount, 2)
                                        }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeOrderModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Stock History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">📋</span>
                <h2 class="text-lg font-semibold text-gray-800">Recent Stock Updates</h2>
                <span class="text-sm text-gray-500">Last 10 updates</span>
            </div>
            <button wire:click="viewAllStockHistory"
                class="text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                View All →
            </button>
        </div>
        @if(isset($stockHistories) && $stockHistories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Old</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">New</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Changed By</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Notes</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($stockHistories as $history)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $history->product->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $history->branch->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $history->old_stock }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $history->new_stock > $history->old_stock ? 'bg-green-100 text-green-800' : '' }}
                                {{ $history->new_stock < $history->old_stock ? 'bg-red-100 text-red-800' : '' }}
                                {{ $history->new_stock == $history->old_stock ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $history->new_stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $history->user->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $history->notes ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No stock updates yet.</p>
        </div>
        @endif
    </div>
</div>