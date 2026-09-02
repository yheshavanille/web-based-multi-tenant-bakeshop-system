<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">🏪 Manage Branches</h1>
                <p class="text-sm text-gray-500">View and manage your bakeshop branches</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-amber-700 text-gray-700 rounded-lg transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Branch
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search branches by name or address..."
                    class="w-full pl-10 pr-10 h-10 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500">
                Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
                <span class="text-gray-400">({{ $branches->count() }} found)</span>
            </p>
            @endif
        </div>

        @if($branches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $branch)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">

                <!-- Header - Fixed height -->
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50 flex-shrink-0">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $branch->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $branch->address }}</p>
                        </div>
                        <span
                            class="px-2.5 py-1 text-xs font-medium rounded-full flex-shrink-0 ml-2
                                    {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <!-- Content - Flexible with padding -->
                <div class="p-4 space-y-3 flex-1 flex flex-col">
                    <!-- Stats - Fixed height row -->
                    <div class="flex items-center gap-4 text-sm flex-shrink-0">
                        <span class="text-gray-600">📦 {{ $branch->products_count ?? 0 }} products</span>
                        <span class="text-gray-600">👥 {{ $branch->employees_count ?? 0 }} employees</span>
                    </div>

                    <!-- Actions - Pushed to bottom with margin-top auto -->
                    <div class="flex flex-col gap-2 pt-2 mt-auto">
                        <button wire:click="viewBranchDetails({{ $branch->id }})"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium text-center">
                            📊 View Branch Details
                        </button>
                        <div class="flex gap-2">
                            <a href="{{ route('livewire.owner.employees.manage', ['branch' => $branch->id]) }}"
                                class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-xs text-center">
                                👥 Manage Employees
                            </a>
                            <a href="{{ route('livewire.owner.products.view-product', ['branch' => $branch->id]) }}"
                                class="flex-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-xs text-center">
                                📦 Manage Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-gray-500">
            <div class="text-4xl mb-2">🏪</div>
            @if(!empty($search))
            <p class="text-lg">No branches found matching "<span class="font-medium text-amber-600">{{ $search
                    }}</span>"</p>
            <p class="text-sm text-gray-400">Try adjusting your search.</p>
            @else
            <p class="text-lg">No branches yet.</p>
            <p class="text-sm text-gray-400">Create your first branch to start managing your bakeshop.</p>
            @endif
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="inline-block mt-4 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                Create Branch →
            </a>
        </div>
        @endif
    </div>

    <!-- Branch Details Modal - Compact & Scrollable -->
    @if($showDetailsModal && $selectedBranch)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm" wire:click="closeDetailsModal"></div>

        <div
            class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">

            <!-- Modal Header - Fixed -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">📊 Branch Performance</h3>
                        <p class="text-sm text-gray-500">
                            {{ $selectedBranch->name }}
                            <span
                                class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $selectedBranch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $selectedBranch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body - Scrollable -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <!-- Stats Grid - 2x2 -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Orders</p>
                        <p class="text-xl font-bold text-blue-600">{{ $branchStats['total_orders'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 text-center border border-green-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Revenue</p>
                        <p class="text-xl font-bold text-green-600">₱{{ number_format($branchStats['total_revenue'] ??
                            0, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3 text-center border border-purple-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Avg. Order Value</p>
                        <p class="text-xl font-bold text-purple-600">₱{{ number_format($branchStats['avg_order_value']
                            ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-3 text-center border border-amber-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Rating</p>
                        <div class="flex items-center justify-center gap-1">
                            <span class="text-xl font-bold text-amber-600">{{ number_format($branchStats['avg_rating']
                                ?? 0, 1) }}</span>
                            <span class="text-amber-500">⭐</span>
                            <span class="text-xs text-gray-400">({{ $branchStats['rating_count'] ?? 0 }})</span>
                        </div>
                    </div>
                </div>

                <!-- Order Status Breakdown - Compact -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Order Status</h4>
                    <div class="grid grid-cols-5 gap-2">
                        <div class="bg-yellow-50 rounded-lg p-2 text-center border border-yellow-200">
                            <p class="text-xs text-gray-500">Pending</p>
                            <p class="text-base font-bold text-yellow-600">{{ $branchStats['pending_orders'] ?? 0 }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-2 text-center border border-blue-200">
                            <p class="text-xs text-gray-500">Preparing</p>
                            <p class="text-base font-bold text-blue-600">{{ $branchStats['preparing_orders'] ?? 0 }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-2 text-center border border-green-200">
                            <p class="text-xs text-gray-500">Ready</p>
                            <p class="text-base font-bold text-green-600">{{ $branchStats['ready_orders'] ?? 0 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2 text-center border border-gray-200">
                            <p class="text-xs text-gray-500">Completed</p>
                            <p class="text-base font-bold text-gray-600">{{ $branchStats['completed_orders'] ?? 0 }}</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-2 text-center border border-red-200">
                            <p class="text-xs text-gray-500">Cancelled</p>
                            <p class="text-base font-bold text-red-600">{{ $branchStats['cancelled_orders'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Products & Employees - Side by side -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">📦 Products</h4>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total</span>
                            <span class="font-bold text-amber-600">{{ $branchStats['total_products'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600">Sold</span>
                            <span class="font-bold text-green-600">{{ $branchStats['total_items_sold'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">👥 Employees</h4>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total</span>
                            <span class="font-bold text-blue-600">{{ $branchStats['total_employees'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600">Order Mgrs</span>
                            <span class="font-bold text-purple-600">{{ $branchStats['order_managers'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600">Inventory Mgrs</span>
                            <span class="font-bold text-indigo-600">{{ $branchStats['inventory_managers'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders - Compact -->
                @if(isset($branchStats['recent_orders']) && $branchStats['recent_orders']->count() > 0)
                <div>
                    <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Recent Orders</h4>
                    <div class="space-y-1.5 max-h-32 overflow-y-auto">
                        @foreach($branchStats['recent_orders'] as $order)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-800">#{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-500">{{ $order->customer->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-amber-600">₱{{ number_format($order->display_total
                                    ?? $order->total_amount, 2) }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Modal Footer - Fixed -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end gap-3">
                <a href="{{ route('livewire.owner.branches.branch-orders', ['branchId' => $selectedBranch->id]) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    View All Orders →
                </a>
                <button wire:click="closeDetailsModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>