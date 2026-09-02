<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-4">
                <a href="{{ route('livewire.owner.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-2xl">🍞</span>
                    <span class="text-xl font-bold text-gray-900">Web-based Multi-Tenant Bakeshop System</span>
                    <span
                        class="ml-2 text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                        👑 Shop Owner
                    </span>
                </a>
            </div>

            <!-- Right Side - Notification Bell + Profile Dropdown -->
            <div class="flex items-center gap-3">
                <!-- ✅ NOTIFICATION BELL -->
                @livewire('components.notification-bell')

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 transition">
                        @php
                        $user = auth()->user();
                        $profilePic = $user->profile_picture;
                        @endphp

                        @if($profilePic)
                        <img src="{{ asset('storage/' . $profilePic) }}?v={{ time() }}" alt="{{ $user->name }}"
                            class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                        @else
                        <div
                            class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                        </div>
                        @endif

                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">

                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                @if($profilePic)
                                <img src="{{ asset('storage/' . $profilePic) }}?v={{ time() }}" alt="{{ $user->name }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                @else
                                <div
                                    class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-400">🛒 Shop Owner</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('livewire.owner.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🏠</span> Dashboard
                        </a>
                        <a href="{{ route('livewire.owner.products.view-product') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">📦</span> Products
                        </a>
                        <a href="{{ route('livewire.owner.category.view-category') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🏷️</span> Categories
                        </a>

                        <a href="{{ route('livewire.owner.branches.manage-cards') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🏪</span> View Branches
                        </a>
                        <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition pl-10">
                            <span class="text-sm">📍</span> Branch Lists
                        </a>

                        @php
                        $firstBranch = auth()->user()->shop?->branches->first();
                        @endphp
                        @if($firstBranch)
                        <a href="{{ route('livewire.owner.branches.branch-orders', ['branchId' => $firstBranch->id]) }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">📋</span> Order & Transaction History
                        </a>
                        @endif

                        <a href="{{ route('livewire.owner.employees.manage') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">👥</span> Employees
                        </a>
                        <a href="{{ route('livewire.owner.shop.edit-shop') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">⚙️</span> Shop Settings
                        </a>

                        <!-- ✅ PRODUCT EDIT HISTORY -->
                        <a href="{{ route('livewire.owner.product-history') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">✏️</span> Product Edit History
                        </a>

                        <!-- ✅ STOCK UPDATE HISTORY -->
                        <a href="{{ route('livewire.owner.stock-history') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">📦</span> Stock Update History
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <a href="{{ route('livewire.customer.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🛒</span> Customer View
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('logout.post') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <span class="text-lg">🚪</span> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>