<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📋 Shop Details</h1>
                <p class="text-sm text-gray-500">View shop details, products, and information</p>
            </div>
            <a href="{{ route('livewire.admin.pages.shops.view-shops') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                ← Back to Shops
            </a>
        </div>

        <!-- Shop Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="flex flex-col md:flex-row">
                <!-- Image -->
                <div class="md:w-1/3 h-64 md:h-auto bg-gray-100 overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" class="w-full h-full object-cover">
                    @else
                    <div
                        class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                        <span class="text-6xl mb-2">🏪</span>
                        <span class="text-sm">No Image</span>
                    </div>
                    @endif
                </div>
                <!-- Details -->
                <div class="flex-1 p-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $shop->shop_name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">📍 {{ $shop->address ?? 'Address not provided' }}</p>
                    <p class="text-sm text-gray-600 mt-3">{{ $shop->description ?? 'No description available.' }}</p>
                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">Owner:</span>
                            <span class="font-medium text-gray-800">{{ $shop->user->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Email:</span>
                            <span class="font-medium text-gray-800">{{ $shop->user->email ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Branches:</span>
                            <span class="font-medium text-gray-800">{{ $branches->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Employees:</span>
                            <span class="font-medium text-gray-800">{{ $employees->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Banners -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Sales</p>
                    <span class="text-2xl">💰</span>
                </div>
                <p class="text-2xl font-bold text-gray-800 mt-2">₱{{ number_format($totalSales, 2) }}</p>
                <p class="text-xs text-green-600 mt-1">From completed orders</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Orders</p>
                    <span class="text-2xl">📋</span>
                </div>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalOrders }}</p>
                <p class="text-xs text-blue-600 mt-1">Completed orders</p>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Products</p>
                    <span class="text-2xl">📦</span>
                </div>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalProducts }}</p>
                <p class="text-xs text-amber-600 mt-1">All branches</p>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border border-purple-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Employees</p>
                    <span class="text-2xl">👥</span>
                </div>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalEmployees }}</p>
                <p class="text-xs text-purple-600 mt-1">Active employees</p>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">📋 Recent Completed Orders</h3>
                    <p class="text-sm text-gray-500">Last 5 completed orders</p>
                </div>
                <button wire:click="openAllOrdersModal"
                    class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    View All →
                </button>
            </div>

            @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Amount</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->branch->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($order->total_amount,
                                2) }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $order->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-sm text-gray-500">No completed orders yet.</p>
            @endif
        </div>

        <!-- Products Section -->
        <div id="products" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">📦 Products</h3>
                    <p class="text-sm text-gray-500">{{ $products->count() }} products found</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <!-- Branch Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Branch</label>
                        <select wire:model.live="selectedBranch"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="all">All Branches</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model.live="selectedCategory"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <!-- Image -->
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        @if($product->image_url)
                        <img src="{{ asset($product->image_url) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <span class="text-4xl">🍰</span>
                            <span class="text-xs">No Image</span>
                        </div>
                        @endif
                    </div>
                    <!-- Content -->
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        <p class="text-lg font-bold text-amber-600 mt-1">₱{{ number_format($product->price, 2) }}</p>

                        <!-- Total Sold & Revenue -->
                        <div class="mt-1 flex items-center gap-3">
                            <span class="text-xs text-gray-500">📊 Sold:</span>
                            <span class="text-xs font-semibold text-green-600">{{ $product->total_sold ?? 0 }}</span>
                            <span class="text-xs text-gray-300">|</span>
                            <span class="text-xs text-gray-500">💰 Revenue:</span>
                            <span class="text-xs font-semibold text-amber-600">₱{{ number_format($product->total_revenue
                                ?? 0, 2) }}</span>
                        </div>

                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($product->branches as $branch)
                            <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                {{ $branch->name }}
                            </span>
                            @endforeach
                        </div>
                        @if($product->description)
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $product->description }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <span class="text-5xl block mb-3">📭</span>
                <p>No products found for this shop.</p>
                <p class="text-sm text-gray-400">Try adjusting your filters.</p>
            </div>
            @endif
        </div>

        <!-- Employees Section -->
        <div id="employees" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">👥 Employees</h3>
                    <p class="text-sm text-gray-500">{{ $employees->count() }} employees assigned</p>
                </div>
            </div>

            @if($employees->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($employees as $employee)
                <div class="border border-gray-100 rounded-lg p-3 bg-gray-50 hover:bg-gray-100 transition">
                    <p class="font-medium text-gray-800 text-sm">{{ $employee->user?->name ?? 'Deleted user' }}</p>
                    <p class="text-xs text-gray-500">{{ $employee->user?->email ?? 'No email' }}</p>
                    <p class="text-xs font-medium text-amber-600 mt-1">{{ $employee->role_label }}</p>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500">No employees assigned to this bakeshop.</p>
            @endif
        </div>

    </div>

    <!-- All Orders Modal -->
    @if($showAllOrdersModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data
        x-init="document.body.classList.add('overflow-hidden')">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeAllOrdersModal">
        </div>

        <div class="relative w-full max-w-5xl overflow-hidden text-left transition-all transform bg-white rounded-2xl shadow-2xl flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">📋 All Completed Orders</h3>
                        <p class="text-sm text-gray-500">{{ $allOrders->count() }} orders found</p>
                    </div>
                    <button wire:click="closeAllOrdersModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 overflow-y-auto flex-1" style="max-height: 60vh;">
                @if($allOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Amount</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($allOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->customer->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->branch->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 font-semibold text-green-600">₱{{
                                    number_format($order->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $order->created_at->format('M d, Y h:i
                                    A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-500">No completed orders found.</p>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <button wire:click="closeAllOrdersModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    @once
    <script>
        document.addEventListener('livewire:init', () => {
                Livewire.on('all-orders-modal-closed', () => {
                    document.body.classList.remove('overflow-hidden');
                });
            });
    </script>
    @endonce
</div>