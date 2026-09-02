<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-4">
                <a href="{{ route('livewire.customer.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-2xl">🍞</span>
                    <span class="text-xl font-bold text-gray-900">Web-based Multi-Tenant Bakeshop System</span>
                </a>

                <!-- Cart Icon with Count -->
                <a href="{{ route('livewire.customer.cart') }}"
                    class="relative p-2 text-gray-600 hover:text-amber-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    @php
                    $cartCount = App\Models\Cart::where('user_id', auth()->id())->count();
                    @endphp
                    @if($cartCount > 0)
                    <span
                        class="absolute -top-1 -right-1 w-5 h-5 bg-amber-500 text-white text-xs rounded-full flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                    @endif
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
                                    <p class="text-xs text-gray-400">{{ $user->phone ?? 'No phone' }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('livewire.customer.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🏠</span> Dashboard
                        </a>
                        <a href="{{ route('livewire.customer.browse-shops') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🏪</span> Browse Shops
                        </a>
                        <a href="{{ route('livewire.customer.orders') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">📋</span> My Orders & Transaction History
                        </a>
                        <a href="{{ route('livewire.customer.profile') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">👤</span> My Profile
                        </a>

                        @if(auth()->user()->hasRole('owner') && auth()->user()->shop &&
                        !auth()->user()->shop->trashed())
                        <a href="{{ route('livewire.owner.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <span class="text-lg">🏪</span> My Shop Dashboard
                        </a>
                        @endif

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