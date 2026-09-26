<div>
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-sm border border-amber-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z">
                    </path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600">Manage the platform and review seller applications.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Users --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        {{-- Total Shops --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Shops</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalShops }}</p>
                </div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        {{-- Pending Sellers --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300 relative">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pending Sellers</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $pendingSellers }}</p>
                </div>
            </div>
            @if($pendingSellers > 0)
            <span
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-md animate-pulse">
                {{ $pendingSellers }}
            </span>
            @endif
        </div>
    </div>

    <!-- TOP 3 BEST SELLING SHOPS (BANNER) -->
    @if(count($topShops) > 0)
    <div
        class="bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 rounded-2xl shadow-sm border border-amber-200 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-gray-800">Top Performing Bakeshops</h2>
            <span class="text-xs text-gray-500 ml-auto">Based on total sales</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($topShops as $index => $data)
            @php
            $rankColors = ['border-amber-400 bg-amber-50', 'border-gray-400 bg-gray-50', 'border-orange-400
            bg-orange-50'];
            @endphp
            <div
                class="bg-white rounded-xl border-2 {{ $rankColors[$index] ?? 'border-gray-200' }} p-5 text-center hover:shadow-lg transition">
                {{-- Rank badge --}}
                <div class="flex justify-center mb-2">
                    @if($index === 0)
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z">
                            </path>
                        </svg>
                    </div>
                    @elseif($index === 1)
                    <div
                        class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 font-bold">
                        #2
                    </div>
                    @elseif($index === 2)
                    <div
                        class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-700 font-bold">
                        #3
                    </div>
                    @endif
                </div>

                <p class="font-bold text-gray-800 text-lg">{{ $data['shop']->shop_name }}</p>
                <p class="text-sm text-gray-500">by {{ $data['owner']->name ?? 'N/A' }}</p>
                <div class="mt-2 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    <span class="font-semibold text-gray-700">{{ $data['avg_rating'] }}</span>
                    <span class="text-xs text-gray-400">rating</span>
                </div>
                <div class="mt-2 flex justify-center gap-6 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Total Revenue</p>
                        <p class="font-bold text-green-600">₱{{ number_format($data['total_sales'], 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Orders</p>
                        <p class="font-bold text-gray-800">{{ $data['total_orders'] }}</p>
                    </div>
                </div>
                @if($data['top_product'] !== 'N/A')
                <div class="mt-2 text-xs text-gray-500">
                    Top: <span class="font-medium text-gray-700">{{ $data['top_product'] }}</span>
                    ({{ $data['top_product_sold'] }} sold)
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <a href="{{ route('livewire.admin.pages.shops.view-shops') }}"
            class="group bg-white border border-gray-200 rounded-2xl p-5 text-center hover:border-green-300 hover:shadow-md transition duration-300">
            <div class="flex justify-center mb-2">
                <div
                    class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center group-hover:scale-110 transition duration-300">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-700 group-hover:text-green-600">View Shops</p>
        </a>

        <a href="{{ route('livewire.admin.pending-sellers') }}"
            class="group bg-white border border-gray-200 rounded-2xl p-5 text-center hover:border-amber-300 hover:shadow-md transition duration-300 relative">
            <div class="flex justify-center mb-2">
                <div
                    class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center group-hover:scale-110 transition duration-300">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-700 group-hover:text-amber-600">Pending Sellers</p>
            @if($pendingSellers > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $pendingSellers }}
            </span>
            @endif
        </a>
    </div>

    <!-- ALL SHOPS PERFORMANCE TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    All Shops Performance
                </h2>
                <p class="text-sm text-gray-500">Ranked by total sales</p>
            </div>
            <a href="{{ route('livewire.admin.pages.shops.view-shops') }}"
                class="inline-flex items-center gap-1 text-sm font-medium text-amber-600 hover:text-amber-700 hover:underline">
                Manage Shops
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>

        @if(count($allShopsRanked) > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Rank</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Shop</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Owner</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Total Sales</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Orders</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Rating</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Top Product</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($allShopsRanked as $index => $data)
                    @php
                    $rankDisplay = $index + 1;
                    @endphp
                    <tr class="hover:bg-gray-50 transition {{ $index < 3 ? 'bg-amber-50/30' : '' }}">
                        <td class="px-4 py-3 font-medium text-gray-700">
                            @if($rankDisplay === 1)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z">
                                    </path>
                                </svg>
                            </span>
                            @elseif($rankDisplay === 2)
                            <span class="text-gray-500 font-bold">#2</span>
                            @elseif($rankDisplay === 3)
                            <span class="text-orange-500 font-bold">#3</span>
                            @else
                            <span class="text-gray-400 font-medium">#{{ $rankDisplay }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $data['shop']->shop_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $data['owner']->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($data['total_sales'], 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $data['total_orders'] }}</td>
                        <td class="px-4 py-3">
                            @if($data['avg_rating'] > 0)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                                <span class="font-medium text-gray-700">{{ $data['avg_rating'] }}</span>
                            </span>
                            @else
                            <span class="text-xs text-gray-400">No reviews</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $data['top_product'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No shops found.</p>
        </div>
        @endif
    </div>

    <!-- Recent Seller Applications -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Recent Seller Applications
                </h2>
                <p class="text-sm text-gray-500">Latest registration requests from sellers</p>
            </div>
            <a href="{{ route('livewire.admin.pending-sellers') }}"
                class="inline-flex items-center gap-1 text-sm font-medium text-amber-600 hover:text-amber-700 hover:underline">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>

        @if($recentApplications->count() > 0)
        <div class="space-y-3">
            @foreach($recentApplications as $app)
            <div
                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition duration-200">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-sm font-bold text-amber-700">
                        {{ strtoupper(substr($app->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $app->user->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $app->shop_name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                {{ $app->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $app->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($app->status) }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $app->created_at->diffForHumans() }}</span>
                    <a href="{{ route('livewire.admin.pending-sellers') }}"
                        class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700">
                        View
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <div class="w-16 h-16 mx-auto mb-2 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
            <p>No seller applications yet.</p>
            <p class="text-sm text-gray-400">Applications will appear here once customers start registering.</p>
        </div>
        @endif
    </div>
</div>