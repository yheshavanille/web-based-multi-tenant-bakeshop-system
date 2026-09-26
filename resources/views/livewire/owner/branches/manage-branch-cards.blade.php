<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Branches</h1>
                <p class="text-sm text-gray-500">View and manage your bakeshop branches</p>
                @if($showDeleted)
                <p class="text-sm text-red-600 mt-1">Showing deleted branches</p>
                @endif
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
                {{-- Show Deleted Toggle --}}
                <button wire:click="toggleDeleted"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                    @if($showDeleted)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Show Active
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Show Deleted
                    @endif
                </button>
                <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Branch
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search branches by name or address..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                    style="position: absolute;">
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
            @php
            $isDeleted = $branch->trashed();
            @endphp
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full {{ $isDeleted ? 'opacity-70 border-red-200' : '' }}">

                <!-- Header - Fixed height -->
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50 flex-shrink-0">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $branch->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $branch->address }}</p>
                        </div>
                        @if($isDeleted)
                        <span
                            class="px-2.5 py-1 text-xs font-medium rounded-full flex-shrink-0 ml-2 bg-gray-100 text-gray-800">
                            Deleted
                        </span>
                        @else
                        <span
                            class="px-2.5 py-1 text-xs font-medium rounded-full flex-shrink-0 ml-2
                                    {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Content - Flexible with padding -->
                <div class="p-4 space-y-3 flex-1 flex flex-col">
                    <!-- Stats - Fixed height row -->
                    <div class="flex items-center gap-4 text-sm flex-shrink-0">
                        <span class="inline-flex items-center gap-1.5 text-gray-600">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $branch->products_count ?? 0 }} products
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-gray-600">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            {{ $branch->employees_count ?? 0 }} employees
                        </span>
                    </div>

                    <!-- Actions - Pushed to bottom with margin-top auto -->
                    <div class="flex flex-col gap-2 pt-2 mt-auto">
                        @if($isDeleted)
                        <!-- Restore button for deleted branches -->
                        <button wire:click="restore({{ $branch->id }})"
                            class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Restore Branch
                        </button>
                        @else
                        <!-- Normal actions for active branches -->
                        <button wire:click="viewBranchDetails({{ $branch->id }})"
                            class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            View Branch Details
                        </button>
                        <div class="flex gap-2">
                            <a href="{{ route('livewire.owner.employees.manage', ['branch' => $branch->id]) }}"
                                class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-xs text-center">
                                Manage Employees
                            </a>
                            <a href="{{ route('livewire.owner.products.view-product', ['branch' => $branch->id]) }}"
                                class="flex-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-xs text-center">
                                Manage Products
                            </a>
                        </div>
                        <button wire:click="delete({{ $branch->id }})"
                            onclick="confirm('Delete this branch? You can restore it later.') || event.stopImmediatePropagation()"
                            class="w-full px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-xs font-medium">
                            Delete Branch
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-gray-500">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            @if(!empty($search))
            <p class="text-lg">No branches found matching "<span class="font-medium text-amber-600">{{ $search
                    }}</span>"</p>
            <p class="text-sm text-gray-400">Try adjusting your search.</p>
            @elseif($showDeleted)
            <p class="text-lg">No deleted branches.</p>
            <p class="text-sm text-gray-400">Deleted branches will appear here.</p>
            @else
            <p class="text-lg">No branches yet.</p>
            <p class="text-sm text-gray-400">Create your first branch to start managing your bakeshop.</p>
            @endif
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                Create Branch
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
        @endif
    </div>

    <!-- Branch Details Modal - Compact & Scrollable -->
    @if($showDetailsModal && $selectedBranch)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeDetailsModal"></div>

        <div
            class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">

            <!-- Modal Header - Fixed -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Branch Performance
                        </h3>
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
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
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
                        <h4
                            class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Products
                        </h4>
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
                        <h4
                            class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Employees
                        </h4>
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
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    View All Orders
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
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