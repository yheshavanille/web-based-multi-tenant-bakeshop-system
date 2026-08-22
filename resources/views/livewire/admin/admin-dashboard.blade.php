<div>
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-sm border border-amber-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center text-3xl shadow-md">
                👑
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600">Manage the platform and review seller applications.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">🏪</div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Shops</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalShops }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">📦</div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition duration-300 relative">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">📋</div>
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

    <!-- ✅ TOP 3 BEST SELLING SHOPS (BANNER) -->
    @if(count($topShops) > 0)
    <div
        class="bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 rounded-2xl shadow-sm border border-amber-200 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-2xl">🏆</span>
            <h2 class="text-lg font-semibold text-gray-800">Top Performing Bakeshops</h2>
            <span class="text-xs text-gray-500 ml-auto">Based on total sales</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($topShops as $index => $data)
            @php
            $rankEmojis = ['🥇', '🥈', '🥉'];
            $rankColors = ['border-amber-400 bg-amber-50', 'border-gray-400 bg-gray-50', 'border-orange-400
            bg-orange-50'];
            @endphp
            <div
                class="bg-white rounded-xl border-2 {{ $rankColors[$index] ?? 'border-gray-200' }} p-5 text-center hover:shadow-lg transition">
                <div class="text-4xl mb-2">{{ $rankEmojis[$index] ?? '#' . ($index + 1) }}</div>
                <p class="font-bold text-gray-800 text-lg">{{ $data['shop']->shop_name }}</p>
                <p class="text-sm text-gray-500">by {{ $data['owner']->name ?? 'N/A' }}</p>
                <div class="mt-2 flex items-center justify-center gap-2">
                    <span class="text-amber-500">⭐</span>
                    <span class="font-semibold text-gray-700">{{ $data['avg_rating'] }}</span>
                    <span class="text-xs text-gray-400">rating</span>
                </div>
                <div class="mt-2 flex justify-center gap-6 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Total Sales</p>
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
            <div class="text-3xl mb-2 group-hover:scale-110 transition duration-300">🏪</div>
            <p class="text-sm font-medium text-gray-700 group-hover:text-green-600">View Shops</p>
        </a>

        <a href="{{ route('livewire.admin.pending-sellers') }}"
            class="group bg-white border border-gray-200 rounded-2xl p-5 text-center hover:border-amber-300 hover:shadow-md transition duration-300 relative">
            <div class="text-3xl mb-2 group-hover:scale-110 transition duration-300">📋</div>
            <p class="text-sm font-medium text-gray-700 group-hover:text-amber-600">Pending Sellers</p>
            @if($pendingSellers > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $pendingSellers }}
            </span>
            @endif
        </a>
    </div>

    <!-- ✅ ALL SHOPS PERFORMANCE TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">📊 All Shops Performance</h2>
                <p class="text-sm text-gray-500">Ranked by total sales</p>
            </div>
            <a href="{{ route('livewire.admin.pages.shops.view-shops') }}"
                class="text-sm font-medium text-amber-600 hover:text-amber-700 hover:underline">
                Manage Shops →
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
                    $rankBadge = $rankDisplay === 1 ? '🥇' : ($rankDisplay === 2 ? '🥈' : ($rankDisplay === 3 ? '🥉' :
                    '#'.$rankDisplay));
                    @endphp
                    <tr class="hover:bg-gray-50 transition {{ $index < 3 ? 'bg-amber-50/30' : '' }}">
                        <td class="px-4 py-3 font-medium text-gray-700">{{ $rankBadge }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $data['shop']->shop_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $data['owner']->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($data['total_sales'], 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $data['total_orders'] }}</td>
                        <td class="px-4 py-3">
                            @if($data['avg_rating'] > 0)
                            <span class="flex items-center gap-1">
                                <span class="text-amber-500 text-xs">⭐</span>
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
                <h2 class="text-lg font-semibold text-gray-800">📋 Recent Seller Applications</h2>
                <p class="text-sm text-gray-500">Latest registration requests from sellers</p>
            </div>
            <a href="{{ route('livewire.admin.pending-sellers') }}"
                class="text-sm font-medium text-amber-600 hover:text-amber-700 hover:underline">
                View All →
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
                        class="text-sm text-amber-600 hover:text-amber-700">
                        View →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <div class="text-4xl mb-2">📭</div>
            <p>No seller applications yet.</p>
            <p class="text-sm text-gray-400">Applications will appear here once customers start registering.</p>
        </div>
        @endif
    </div>
</div>