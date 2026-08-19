<div>
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center text-2xl">
                🏪
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
                                @if($item->product->image_url)
                                <img src="{{ asset($item->product->image_url) }}"
                                    class="w-8 h-8 rounded-lg object-cover">
                                @endif
                                {{ $item->product->name }}
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

    <!-- Branch Performance with Ratings -->
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

    <!-- Employees Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">👥</span>
                <h2 class="text-lg font-semibold text-gray-800">Employees</h2>
                <span class="text-sm text-gray-500">{{ $employeesCount }} employees assigned</span>
            </div>
            <button wire:click="openEmployeesModal" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All →
            </button>
        </div>

        @if($employeesCount > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @php
            $recentEmployees = \App\Models\Employee::with(['user', 'branch'])
            ->where('shop_id', Auth::user()->shop->id)
            ->limit(6)
            ->get();
            @endphp
            @foreach($recentEmployees as $employee)
            <div class="border border-gray-100 rounded-lg p-3 bg-gray-50 hover:bg-gray-100 transition">
                <p class="font-medium text-gray-800 text-sm">{{ $employee->user?->name ?? 'Deleted user' }}</p>
                <p class="text-xs text-gray-500">{{ $employee->user?->email ?? 'No email' }}</p>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xs font-medium text-amber-600">{{ $employee->role_label }}</p>
                    <span
                        class="text-xs px-2 py-0.5 rounded-full {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-500">No employees assigned yet.</p>
        @endif
    </div>

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
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $review->customer->name ?? 'Anonymous' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $review->branch->name ?? 'N/A' }}</td>
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

    <!-- Low Stock Alert -->
    @if(isset($lowStockItems) && $lowStockItems->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-2">
            <span class="text-xl">⚠️</span>
            <h3 class="font-semibold text-yellow-800">Low Stock Alert</h3>
            <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full ml-auto">
                {{ $lowStockItems->count() }} items
            </span>
        </div>
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($lowStockItems as $product)
            @php
            $stock = $product->branches->first()?->pivot->stock ?? 0;
            @endphp
            <span class="px-3 py-1 bg-white border border-yellow-200 rounded-full text-sm text-yellow-800">
                {{ $product->name }}: {{ $stock }} left
            </span>
            @endforeach
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

    <!-- Recent Branches -->
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

    <!-- Stock History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">📋 Recent Stock Updates</h2>
            <span class="text-xs text-gray-500">Last 10 updates</span>
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

    <!-- Employees Modal -->
    @if($showAllEmployeesModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data
        x-init="document.body.classList.add('overflow-hidden')">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeEmployeesModal">
        </div>

        <div class="relative w-full max-w-5xl overflow-hidden text-left transition-all transform bg-white rounded-2xl shadow-2xl flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">👥 All Employees</h3>
                        <p class="text-sm text-gray-500">{{ $allEmployees->count() }} employees found</p>
                    </div>
                    <button wire:click="closeEmployeesModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 overflow-y-auto flex-1" style="max-height: 60vh;">
                <!-- Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Branch</label>
                        <select wire:model.live="employeeBranchFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="all">All Branches</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                        <select wire:model.live="employeeRoleFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="all">All Roles</option>
                            <option value="order_manager">📋 Order Manager</option>
                            <option value="inventory_manager">📦 Inventory Manager</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" wire:model.live="employeeSearch" placeholder="Search by name or email..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                </div>

                <!-- Employees Table -->
                @if($allEmployees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Employee</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Role</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($allEmployees as $employee)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">{{ $employee->user?->name ?? 'Deleted user' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $employee->user?->email ?? 'No email' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $employee->role === 'order_manager' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $employee->role_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">📍 {{ $employee->branch->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $employee->is_active ? '🟢 Active' : '🔴 Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-500">No employees found matching your filters.</p>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <button wire:click="closeEmployeesModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>