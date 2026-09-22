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

        <!-- ✅ Best Selling Products -->
        @if(isset($bestSellers) && $bestSellers->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xl">🏆</span>
                <h3 class="text-lg font-semibold text-gray-800">Best Selling Products</h3>
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
                            <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($item->total_revenue,
                                2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">📋 Recent Order Updates</h3>
                    <p class="text-sm text-gray-500">Last 5 orders (all statuses)</p>
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
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Amount</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Updated</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->branch->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->item_count }} items</td>
                            <td class="px-4 py-3 font-semibold text-green-600">₱{{
                                number_format($order->display_total ?? $order->total_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $order->status_summary }}
                                </span>
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
            <p class="text-sm text-gray-500">No orders yet.</p>
            @endif
        </div>

        <!-- ✅ RECENT EMPLOYEE ACTIVITIES -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">👥 Recent Employee Activities</h3>
                    <p class="text-sm text-gray-500">Last 5 activities from this shop's employees</p>
                </div>
                <a href="{{ route('livewire.admin.employee-activities', ['shop' => $shop->id]) }}"
                    class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    View All →
                </a>
            </div>

            @if($recentEmployeeActivities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Employee</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Description</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($recentEmployeeActivities as $activity)
                        @php
                        // ✅ Safe accessors
                        $actUser = $activity->employee?->user;
                        $actPic = $actUser?->profile_picture;
                        $actName = $actUser?->name ?? 'Deleted User';
                        $actInitials = strtoupper(substr($actName, 0, 2));
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($actPic)
                                    <img src="{{ asset('storage/' . $actPic) }}"
                                        class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                                    @else
                                    <div
                                        class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                        {{ $actInitials }}
                                    </div>
                                    @endif
                                    <p class="font-medium text-gray-800">{{ $actName }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $activity->employee?->branch?->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-xs px-2 py-1 rounded-full
                                    {{ $activity->action === 'password_changed_self' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $activity->action === 'password_changed_by_owner' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $activity->action === 'profile_updated' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $activity->action === 'employee_created' ? 'bg-amber-100 text-amber-800' : '' }}">
                                    {{ $activity->action_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $activity->description }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $activity->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <span class="text-3xl block mb-2">📭</span>
                <p class="text-sm">No employee activities for this shop yet.</p>
            </div>
            @endif
        </div>

        <!-- ✅ RECENT PRODUCT UPDATES -->
        @if(isset($productEditHistories) && $productEditHistories->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">✏️</span>
                    <h3 class="text-lg font-semibold text-gray-800">Recent Product Updates</h3>
                    <span class="text-sm text-gray-500">Last 10 updates</span>
                </div>
                <button wire:click="viewAllProductHistory"
                    class="text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                    View All →
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Field</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Old Value</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">New Value</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Updated By</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($productEditHistories as $history)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $history->product?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    {{ $history->field === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $history->field === 'name' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $history->field === 'price' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $history->field === 'category_id' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $history->field === 'description' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $history->field === 'image_url' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $history->field === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $history->field === 'restored' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $history->field)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-sm">
                                @if($history->field === 'price')
                                ₱{{ number_format($history->old_value ?? 0, 2) }}
                                @elseif($history->field === 'image_url')
                                <span class="text-xs text-gray-400">{{ $history->old_value ? 'Old image' : 'No image'
                                    }}</span>
                                @elseif($history->field === 'deleted')
                                <span class="text-xs text-red-600">{{ $history->old_value }}</span>
                                @else
                                {{ $history->old_value ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm">
                                @if($history->field === 'price')
                                ₱{{ number_format($history->new_value ?? 0, 2) }}
                                @elseif($history->field === 'image_url')
                                <span class="text-xs text-green-600">{{ $history->new_value ? 'New image' : 'Removed'
                                    }}</span>
                                @elseif($history->field === 'created')
                                <span class="text-xs text-green-600">Product created</span>
                                @elseif($history->field === 'deleted')
                                <span class="text-xs text-red-600">{{ $history->new_value }}</span>
                                @elseif($history->field === 'restored')
                                <span class="text-xs text-green-600">{{ $history->new_value }}</span>
                                @else
                                {{ $history->new_value ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $history->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ✅ RECENT STOCK UPDATES -->
        @if(isset($stockHistories) && $stockHistories->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📋</span>
                    <h3 class="text-lg font-semibold text-gray-800">Recent Stock Updates</h3>
                    <span class="text-sm text-gray-500">Last 10 updates</span>
                </div>
                <button wire:click="viewAllStockHistory"
                    class="text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                    View All →
                </button>
            </div>
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
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $history->product?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $history->branch?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $history->old_stock }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $history->new_stock > $history->old_stock ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $history->new_stock < $history->old_stock ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $history->new_stock == $history->old_stock ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $history->new_stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $history->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $history->notes ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Products Section -->
        <div id="products" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">📦 Products</h3>
                    <p class="text-sm text-gray-500">{{ $products->count() }} products found</p>
                </div>
                <div class="flex flex-wrap gap-3">
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

            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
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
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        <p class="text-lg font-bold mt-1">
                            @if($product->isDiscounted())
                            <span class="text-red-600">₱{{ number_format($product->getDiscountedPrice(), 2) }}</span>
                            <span class="text-sm text-gray-400 line-through ml-2">₱{{ number_format($product->price, 2)
                                }}</span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-1">{{
                                $product->getDiscountLabel() }}</span>
                            @else
                            <span class="text-amber-600">₱{{ number_format($product->price, 2) }}</span>
                            @endif
                        </p>

                        <div class="mt-1 flex items-center gap-3">
                            <span class="text-xs text-gray-500">📊 Sold:</span>
                            <span class="text-xs font-semibold text-green-600">{{ $product->total_sold ?? 0 }}</span>
                            <span class="text-xs text-gray-300">|</span>
                            <span class="text-xs text-gray-500">💰 Revenue:</span>
                            <span class="text-xs font-semibold text-amber-600">₱{{ number_format($product->total_revenue
                                ?? 0, 2) }}</span>
                        </div>

                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                {{ $product->current_stock > 10 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $product->current_stock <= 10 && $product->current_stock > 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $product->current_stock <= 0 ? 'bg-red-100 text-red-800' : '' }}">
                                📦 {{ $product->current_stock }} in stock
                            </span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">
                                ⭐ {{ $product->product_reviews_count ?? 0 }} reviews
                            </span>
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

                        <button wire:click="viewProductDetails({{ $product->id }})"
                            class="mt-3 w-full px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                            📊 View Details
                        </button>
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
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeAllOrdersModal">
        </div>

        <div class="relative w-full max-w-5xl overflow-hidden text-left transition-all transform bg-white rounded-2xl shadow-2xl flex flex-col"
            style="max-height: 90vh;">

            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">📋 All Orders</h3>
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

            <div class="px-6 py-4 overflow-y-auto flex-1" style="max-height: 60vh;">
                @if($allOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Amount</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($allOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->customer->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->branch->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->item_count }} items</td>
                                <td class="px-4 py-3 font-semibold text-green-600">₱{{
                                    number_format($order->display_total ?? $order->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-medium text-gray-700">
                                        {{ $order->status_summary }}
                                    </span>
                                    @if($order->cancelled_count > 0)
                                    <span class="text-xs text-red-500">⚠️</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $order->created_at->format('M d, Y h:i
                                    A') }}</td>
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
                <p class="text-sm text-gray-500">No orders found.</p>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <button wire:click="closeAllOrdersModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ✅ Order Details Modal -->
    @if($showOrderDetailsModal && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
        style="overscroll-behavior: contain;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeOrderDetailsModal">
        </div>

        <div class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 92vh;">

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
                    <button wire:click="closeOrderDetailsModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order Date</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedOrder->created_at->format('M d, Y h:i
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
                            {{ $selectedOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
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

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">📦 Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Product</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Qty</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Price</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Subtotal</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Notes</th>
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
                                            {{ $item->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $item->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 text-xs">
                                        @if($item->notes)
                                        <span class="italic">"{{ $item->notes }}"</span>
                                        @else
                                        <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                @php $b = $this->getBreakdown(); @endphp

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
                    </div>
                </div>
                @endif

            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeOrderDetailsModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif

    <!-- ✅ PRODUCT HISTORY MODAL -->
    @if($showProductHistoryModal && $allProductHistories)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeProductHistoryModal"></div>

        <div
            class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">✏️ All Product Updates</h3>
                        <p class="text-sm text-gray-500">{{ $allProductHistories->count() }} total updates</p>
                    </div>
                    <button wire:click="closeProductHistoryModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Field</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Old Value</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">New Value</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Updated By</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($allProductHistories as $history)
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $history->product?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    {{ $history->field === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $history->field === 'name' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $history->field === 'price' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $history->field === 'category_id' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $history->field === 'description' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $history->field === 'image_url' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $history->field === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $history->field === 'restored' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $history->field)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-500 text-sm">
                                @if($history->field === 'price')
                                ₱{{ number_format($history->old_value ?? 0, 2) }}
                                @elseif($history->field === 'image_url')
                                <span class="text-xs text-gray-400">{{ $history->old_value ? 'Old image' : 'No image'
                                    }}</span>
                                @elseif($history->field === 'deleted')
                                <span class="text-xs text-red-600">{{ $history->old_value }}</span>
                                @elseif($history->field === 'created')
                                <span class="text-xs text-gray-400">—</span>
                                @else
                                {{ $history->old_value ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-700 text-sm">
                                @if($history->field === 'price')
                                ₱{{ number_format($history->new_value ?? 0, 2) }}
                                @elseif($history->field === 'image_url')
                                <span class="text-xs text-green-600">{{ $history->new_value ? 'New image' : 'Removed'
                                    }}</span>
                                @elseif($history->field === 'created')
                                <span class="text-xs text-green-600">Product created</span>
                                @elseif($history->field === 'deleted')
                                <span class="text-xs text-red-600">{{ $history->new_value }}</span>
                                @elseif($history->field === 'restored')
                                <span class="text-xs text-green-600">{{ $history->new_value }}</span>
                                @else
                                {{ $history->new_value ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-600">{{ $history->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-2 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeProductHistoryModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ✅ STOCK HISTORY MODAL -->
    @if($showStockHistoryModal && $allStockHistories)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeStockHistoryModal"></div>

        <div
            class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">📦 All Stock Updates</h3>
                        <p class="text-sm text-gray-500">{{ $allStockHistories->count() }} total updates</p>
                    </div>
                    <button wire:click="closeStockHistoryModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Old Stock</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">New Stock</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Changed By</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Notes</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($allStockHistories as $history)
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $history->product?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $history->branch?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $history->old_stock }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $history->new_stock > $history->old_stock ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $history->new_stock < $history->old_stock ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $history->new_stock == $history->old_stock ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $history->new_stock }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-600">{{ $history->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $history->notes ?? '-' }}</td>
                            <td class="px-4 py-2 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeStockHistoryModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ✅ PRODUCT DETAILS MODAL -->
    @if($showProductModal && $selectedProduct)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="overscroll-behavior: contain;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeProductModal"></div>

        <div class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 90vh;">

            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🍰</span>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">📦 Product Details</h3>
                            <p class="text-sm text-gray-500">{{ $selectedProduct->name }}</p>
                        </div>
                    </div>
                    <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                        @if($selectedProduct->image_url)
                        <img src="{{ asset($selectedProduct->image_url) }}" class="w-full h-full object-cover">
                        @else
                        <div
                            class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                            <span class="text-5xl mb-2">🍰</span>
                            <span class="text-sm">No Image</span>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold text-gray-800">{{ $selectedProduct->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $selectedProduct->category->name ?? 'Uncategorized' }}</p>
                        <p class="text-2xl font-bold">
                            @if($selectedProduct->isDiscounted())
                            <span class="text-red-600">₱{{ number_format($selectedProduct->getDiscountedPrice(), 2)
                                }}</span>
                            <span class="text-sm text-gray-400 line-through ml-2">₱{{
                                number_format($selectedProduct->price, 2) }}</span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-1">{{
                                $selectedProduct->getDiscountLabel() }}</span>
                            @else
                            <span class="text-amber-600">₱{{ number_format($selectedProduct->price, 2) }}</span>
                            @endif
                        </p>
                        @php
                        $totalStock = $selectedProduct->branches->sum('pivot.stock');
                        @endphp
                        <p class="text-sm text-gray-600">📦 Stock: <span class="font-medium">{{ $totalStock }}</span>
                            units available</p>
                        @if($selectedProduct->description)
                        <p class="text-sm text-gray-600 mt-2 border-t border-gray-100 pt-2">{{
                            $selectedProduct->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Sold</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $productAnalytics['total_sold'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Orders</p>
                        <p class="text-2xl font-bold text-green-600">{{ $productAnalytics['total_orders'] ?? 0 }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Revenue</p>
                        <p class="text-2xl font-bold text-amber-600">₱{{
                            number_format($productAnalytics['total_revenue'] ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">📍 Stock by Branch</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forelse($selectedProduct->branches as $branch)
                        <div class="border border-gray-100 rounded-lg p-3 bg-gray-50 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">{{ $branch->name }}</span>
                            <span
                                class="text-sm font-semibold {{ $branch->pivot->stock > 5 ? 'text-green-600' : ($branch->pivot->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $branch->pivot->stock }}
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 col-span-2">No branches assigned.</p>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700">⭐ Customer Reviews</h4>
                        <span class="text-xs text-gray-500">{{ $selectedProduct->productReviews->count() }}
                            reviews</span>
                    </div>

                    @if($selectedProduct->productReviews->count() > 0)
                    <div class="space-y-4 max-h-60 overflow-y-auto pr-2">
                        @foreach($selectedProduct->productReviews as $review)
                        <div class="border-b border-gray-100 pb-3 last:border-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $review->customer->name ??
                                        'Anonymous' }}</p>
                                    <div class="flex items-center gap-1 text-amber-500 text-sm">
                                        {{ str_repeat('⭐', $review->rating) }}
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->review)
                            <p class="text-sm text-gray-600 mt-1">{{ $review->review }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-gray-500">
                        <p class="text-sm">No reviews yet for this product.</p>
                    </div>
                    @endif
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeProductModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>