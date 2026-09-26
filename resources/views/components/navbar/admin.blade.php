<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <a href="{{ route('livewire.admin.admin-dashboard') }}" class="flex items-center gap-2">
                    <span class="text-xl font-bold text-gray-900">Web-based Multi-Tenant Bakeshop System</span>
                    <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Admin</span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <livewire:components.notification-bell :context="'admin'" :key="'admin-notif'" />

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 transition">
                        @php
                        $user = auth()->user();
                        $profilePic = $user->profile_picture;
                        @endphp

                        @if($profilePic)
                        <img src="{{ asset('storage/' . $profilePic) }}?v={{ time() }}" alt="{{ $user->name }}"
                            class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">
                        @else
                        <div
                            class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
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
                        class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 max-h-[calc(100vh-5rem)] overflow-y-auto">

                        {{-- Profile Header --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                @if($profilePic)
                                <img src="{{ asset('storage/' . $profilePic) }}?v={{ time() }}" alt="{{ $user->name }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">
                                @else
                                <div
                                    class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-400">Super Admin</p>
                                </div>
                            </div>
                        </div>

                        {{-- ============ OVERVIEW ============ --}}
                        <p class="px-4 pt-3 pb-1 text-xs font-bold uppercase tracking-wider text-gray-700">
                            Overview
                        </p>

                        <a href="{{ route('livewire.admin.admin-dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Dashboard
                        </a>

                        {{-- ============ MANAGEMENT ============ --}}
                        <p class="px-4 pt-3 pb-1 text-xs font-bold uppercase tracking-wider text-gray-700">
                            Management
                        </p>

                        <a href="{{ route('livewire.admin.pages.shops.view-shops') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            View Shops
                        </a>
                        <a href="{{ route('livewire.admin.pages.users.manage-users') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Manage Users
                        </a>

                        {{-- ============ MODERATION ============ --}}
                        <p class="px-4 pt-3 pb-1 text-xs font-bold uppercase tracking-wider text-gray-700">
                            Moderation
                        </p>

                        <a href="{{ route('livewire.admin.pending-sellers') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Pending Sellers
                            @if(\App\Models\SellerRegistration::where('status', 'pending')->count() > 0)
                            <span class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                {{ \App\Models\SellerRegistration::where('status', 'pending')->count() }}
                            </span>
                            @endif
                        </a>

                        <a href="{{ route('livewire.admin.flagged-reviews') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Flagged Reviews
                            @php
                            $flaggedCount = \App\Models\ServiceReview::where('moderation_status',
                            'pending_review')->count()
                            + \App\Models\ProductReview::where('moderation_status', 'pending_review')->count();
                            @endphp
                            @if($flaggedCount > 0)
                            <span class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                {{ $flaggedCount }}
                            </span>
                            @endif
                        </a>

                        {{-- ============ ACCOUNT ============ --}}
                        <div class="border-t border-gray-100 my-2"></div>

                        <p class="px-4 pt-1 pb-1 text-xs font-bold uppercase tracking-wider text-gray-700">
                            Account
                        </p>

                        <a href="{{ route('livewire.admin.profile') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            My Profile
                        </a>

                        <div class="border-t border-gray-100 my-2"></div>

                        <form method="POST" action="{{ route('logout.post') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>