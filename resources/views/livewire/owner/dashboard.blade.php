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
                <div class="w-full h-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-500">Manage your bakeshop and track performance.</p>
                @if($shopRatingCount > 0)
                <div class="flex items-center gap-2 mt-1">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    <span class="font-semibold text-gray-800 text-sm">{{ number_format($shopRating, 1) }}</span>
                    <span class="text-sm text-gray-500">({{ $shopRatingCount }} reviews)</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SALES OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Revenue --}}
        <div
            class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Revenue</p>
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">₱{{ number_format($totalSales, 2) }}</p>
            <p class="text-xs text-green-600 mt-1">From completed orders</p>
        </div>

        {{-- Total Orders --}}
        <div
            class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Orders</p>
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalOrders }}</p>
            <p class="text-xs text-blue-600 mt-1">Completed orders</p>
        </div>

        {{-- Total Products --}}
        <div
            class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Products</p>
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalProducts }}</p>
            <p class="text-xs text-amber-600 mt-1">All branches</p>
        </div>

        {{-- Total Employees --}}
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border border-purple-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Total Employees</p>
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $employeesCount }}</p>
            <p class="text-xs text-purple-600 mt-1">Active employees</p>
        </div>
    </div>

    <!-- RECENT PENDING ORDERS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-gray-800">Recent Pending Orders</h2>
                <span class="text-sm text-gray-500">Last 5 pending orders</span>
            </div>
            <a href="{{ route('livewire.owner.orders', ['status' => 'pending']) }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>

        @if($recentPendingOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Items</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Placed</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($recentPendingOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->branch?->name ?? 'N/A' }}</td>
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
            <div class="w-14 h-14 mx-auto mb-2 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-sm">No pending orders right now.</p>
        </div>
        @endif
    </div>

    <!-- Recent Order Updates -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-gray-800">Recent Order Updates</h2>
                <span class="text-sm text-gray-500">Last 10 orders</span>
            </div>
            <a href="{{ route('livewire.owner.orders') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
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
                        <td class="px-4 py-3 font-medium text-amber-600">
                            ₱{{ number_format($order->display_total ?? $order->total_amount, 2) }}
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

    <!-- RECENT EMPLOYEE ACTIVITIES -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-gray-800">Recent Employee Activities</h2>
                <span class="text-sm text-gray-500">Last 5 activities</span>
            </div>
            <a href="{{ route('livewire.owner.employee-activities') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>

        @if($recentEmployeeActivities->count() > 0)
        <div class="space-y-3">
            @foreach($recentEmployeeActivities as $activity)
            @php
            $empUser = $activity->employee?->user;
            $pic = $empUser?->profile_picture;
            $empName = $empUser?->name ?? 'Deleted User';
            $empInitials = strtoupper(substr($empName, 0, 2));
            @endphp
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                @if($pic)
                <img src="{{ asset('storage/' . $pic) }}"
                    class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0">
                @else
                <div
                    class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                    {{ $empInitials }}
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">
                        {{ $empName }}
                    </p>
                    <p class="text-xs text-gray-600 truncate">{{ $activity->description }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $activity->action === 'password_changed_self' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $activity->action === 'password_changed_by_owner' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $activity->action === 'profile_updated' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $activity->action === 'employee_created' ? 'bg-amber-100 text-amber-800' : '' }}">
                        {{ $activity->action_label }}
                    </span>
                    <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <div class="w-14 h-14 mx-auto mb-2 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
            <p class="text-sm">No employee activities yet.</p>
        </div>
        @endif
    </div>

    <!-- Best Selling Products -->
    @if($bestSellers->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
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
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                                </svg>
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
            <h2 class="text-lg font-semibold text-gray-800 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Branch Performance
            </h2>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700">
                Manage Branches
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
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
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                                <span class="text-gray-700">{{ number_format($branch['rating'], 1) }}</span>
                                <span class="text-xs text-gray-400">({{ $branch['rating_count'] }})</span>
                            </span>
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

    <!-- RECENT PRODUCT UPDATES -->
    @if(isset($productEditHistories) && $productEditHistories->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-gray-800">Recent Product Updates</h2>
                <span class="text-sm text-gray-500">Last 10 updates</span>
            </div>
            <button wire:click="viewAllProductHistory"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Field</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Old Value</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">New Value</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Updated By</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($productEditHistories as $history)
                    @php
                    $branchName = $history->product?->branches->first()?->name ?? 'N/A';
                    @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $history->product?->name ?? 'Product Deleted'
                            }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $branchName }}</td>
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

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('livewire.owner.branches.manage-branches') }}"
            class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center hover:bg-blue-100 transition">
            <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <p class="text-sm font-medium text-blue-700">Manage Branches</p>
        </a>
        <a href="{{ route('livewire.owner.employees.manage') }}"
            class="bg-green-50 border border-green-200 rounded-xl p-4 text-center hover:bg-green-100 transition">
            <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <p class="text-sm font-medium text-green-700">Manage Employees</p>
        </a>
        <a href="{{ route('livewire.owner.shop.edit-shop') }}"
            class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center hover:bg-purple-100 transition">
            <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-purple-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <p class="text-sm font-medium text-purple-700">Shop Settings</p>
        </a>
    </div>

    <!-- Your Branches -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Your Branches</h2>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
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
                <div class="mt-2 text-sm text-gray-600 inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    {{ $branch->products_count ?? 0 }} products
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 text-gray-500">
            <p>No branches yet.</p>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="inline-flex items-center gap-1 text-amber-600 hover:underline mt-1">
                Create your first branch
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
        @endif
    </div>

    <!-- Order Details Modal -->
    @if($showOrderModal && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeOrderModal"></div>

        <div class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 92vh;">

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
                            {{ $selectedOrder->status === 'partially_completed' ? 'bg-yellow-100 text-yellow-800' : '' }}
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
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Order Items
                    </h4>
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
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($selectedOrder->items as $item)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $item->product?->name ?? 'N/A' }}
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
                                    <td colspan="7" class="px-4 pt-3 pb-1">
                                        <div
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            Original Order
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">Subtotal:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['original_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['original_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-gray-800">
                                        Original Total:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-gray-800">₱{{
                                        number_format($b['original_total'], 2) }}</td>
                                </tr>

                                @if($b['amount_charged'] > 0)
                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200">
                                        <div
                                            class="text-xs font-semibold text-green-600 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Charged to Customer
                                        </div>
                                    </td>
                                </tr>
                                @foreach($b['charged_items'] as $item)
                                <tr>
                                    <td colspan="5" class="px-4 py-0.5 text-right text-xs text-gray-500">• {{
                                        $item['name'] }} ({{ $item['quantity'] }}x)</td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600">₱{{
                                        number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">Completed Items:
                                    </td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-green-700">
                                        Amount Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-green-600">₱{{
                                        number_format($b['amount_charged'], 2) }}</td>
                                </tr>
                                @endif

                                @if($b['amount_not_charged'] > 0)
                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200">
                                        <div
                                            class="text-xs font-semibold text-red-600 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                </path>
                                            </svg>
                                            Not Charged
                                        </div>
                                    </td>
                                </tr>
                                @foreach($b['not_charged_items'] as $item)
                                <tr>
                                    <td colspan="5" class="px-4 py-0.5 text-right text-xs text-gray-500">• {{
                                        $item['name'] }} ({{ $item['quantity'] }}x)</td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600">₱{{
                                        number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">No Show /
                                        Cancelled:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['not_charged_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['not_charged_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-red-700">
                                        Amount Not Charged:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-red-600">₱{{
                                        number_format($b['amount_not_charged'], 2) }}</td>
                                </tr>
                                @endif

                                @if($b['amount_outstanding'] > 0)
                                <tr>
                                    <td colspan="7" class="px-4 pt-3 pb-1 border-t border-gray-200">
                                        <div
                                            class="text-xs font-semibold text-amber-600 uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Outstanding
                                        </div>
                                    </td>
                                </tr>
                                @foreach($b['outstanding_items'] as $item)
                                <tr>
                                    <td colspan="5" class="px-4 py-0.5 text-right text-xs text-gray-500">• {{
                                        $item['name'] }} ({{ $item['quantity'] }}x)</td>
                                    <td colspan="2" class="px-4 py-0.5 text-xs text-gray-600">₱{{
                                        number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">Pending +
                                        Preparing + Ready:</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['outstanding_subtotal'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm text-gray-600">VAT (12%):</td>
                                    <td colspan="2" class="px-4 py-1 font-medium text-gray-800">₱{{
                                        number_format($b['outstanding_vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-1 text-right text-sm font-semibold text-amber-700">
                                        Amount Outstanding:</td>
                                    <td colspan="2" class="px-4 py-1 font-bold text-amber-600">₱{{
                                        number_format($b['amount_outstanding'], 2) }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        Customer Review
                    </h4>
                    @php
                    $serviceReview = $selectedOrder->serviceReview;
                    @endphp
                    @if($serviceReview)
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++) @if($i <=$serviceReview->rating)
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @endif
                                    @endfor
                            </div>
                            <span class="text-sm text-gray-500">({{ $serviceReview->rating }}/5)</span>
                            @if($serviceReview->employee_rating)
                            <span class="inline-flex items-center gap-1 text-xs text-gray-400 ml-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Employee: {{ $serviceReview->employee_rating }}/5
                            </span>
                            @endif
                        </div>
                        @if($serviceReview->review)
                        <p class="text-sm text-gray-700 italic">"{{ $serviceReview->review }}"</p>
                        @endif
                        <p class="text-xs text-gray-400">Reviewed {{ $serviceReview->created_at->diffForHumans() }}</p>
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500 text-sm">
                        <p>No review yet for this order.</p>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Product Reviews
                    </h4>
                    @php
                    $productReviews = $selectedOrder->productReviews;
                    @endphp
                    @if($productReviews && $productReviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($productReviews as $productReview)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $productReview->product?->name ??
                                        'N/A' }}</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        <div class="flex items-center gap-0.5">
                                            @for($i = 1; $i <= 5; $i++) @if($i <=$productReview->rating)
                                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                    </path>
                                                </svg>
                                                @else
                                                <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                    </path>
                                                </svg>
                                                @endif
                                                @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">({{ $productReview->rating }}/5)</span>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $productReview->created_at->diffForHumans()
                                    }}</span>
                            </div>
                            @if($productReview->review)
                            <p class="text-sm text-gray-600 mt-1 italic">"{{ $productReview->review }}"</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500 text-sm">
                        <p>No product reviews yet for this order.</p>
                    </div>
                    @endif
                </div>
            </div>

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
                <h2 class="text-lg font-semibold text-gray-800">Recent Stock Updates</h2>
                <span class="text-sm text-gray-500">Last 10 updates</span>
            </div>
            <button wire:click="viewAllStockHistory"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
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
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $history->product?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $history->branch?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $history->old_stock }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
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
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No stock updates yet.</p>
        </div>
        @endif
    </div>

    <!-- Product History Modal -->
    @if($showProductHistoryModal && $allProductHistories)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeProductHistoryModal"></div>

        <div
            class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            All Product Updates
                        </h3>
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
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Field</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Old Value</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">New Value</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Updated By</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($allProductHistories as $history)
                        @php
                        $branchName = $history->product?->branches->first()?->name ?? 'N/A';
                        @endphp
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $history->product?->name ?? 'Product
                                Deleted' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $branchName }}</td>
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

    <!-- Stock History Modal -->
    @if($showStockHistoryModal && $allStockHistories)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeStockHistoryModal"></div>

        <div
            class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            All Stock Updates
                        </h3>
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

</div>