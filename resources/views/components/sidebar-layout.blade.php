@props([
'role' => 'owner', // owner | employee | admin | customer
])

<div x-data="{
        sidebarOpen: JSON.parse(localStorage.getItem('sidebar-{{ $role }}') ?? 'true'),
        mobileOpen: false,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebar-{{ $role }}', JSON.stringify(this.sidebarOpen));
        }
    }" class="min-h-screen bg-gray-50 dark:bg-slate-900" x-cloak>
    {{-- ==================== MOBILE OVERLAY ==================== --}}
    <div x-show="mobileOpen" x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileOpen = false"
        class="fixed inset-0 z-40 bg-gray-900/60 lg:hidden" style="display: none;"></div>

    {{-- ==================== SIDEBAR ==================== --}}
    <aside :class="[sidebarOpen ? 'lg:w-64' : 'lg:w-[72px]', mobileOpen ? '!translate-x-0' : '']"
        class="fixed top-0 left-0 z-50 h-screen bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex flex-col transition-all duration-300 ease-in-out w-64 -translate-x-full lg:translate-x-0">
        {{-- Top spacer --}}
        <div class="h-16 flex-shrink-0 border-b border-gray-100 dark:border-slate-700"></div>

        {{-- Sidebar content --}}
        <nav @click="if ($event.target.closest('a')) mobileOpen = false" class="flex-1 overflow-x-hidden overflow-y-auto py-4 space-y-1">
            {{ $sidebar }}
        </nav>
    </aside>

    {{-- ==================== MAIN COLUMN ==================== --}}
    <div :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-[72px]'" class="transition-all duration-300 ease-in-out">
        {{-- ==================== TOP BAR ==================== --}}
        <header
            class="sticky top-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 h-16">
            <div class="h-full px-4 sm:px-6 flex items-center gap-3">

                {{-- Hamburger --}}
                <button @click="if (window.innerWidth >= 1024) { toggleSidebar(); } else { mobileOpen = !mobileOpen; }"
                    class="p-2 -ml-2 text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-slate-100 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition"
                    aria-label="Toggle sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                {{-- Brand + role --}}
                <div class="flex items-center gap-3 min-w-0">
                    <span class="text-base sm:text-lg font-bold text-gray-900 dark:text-slate-100 truncate">
                        Web-based Multi-Tenant Bakeshop System
                    </span>
                    <span
                        class="hidden sm:inline text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap
                        @if($role === 'owner') bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/40 dark:text-amber-200 dark:border-amber-800
                        @elseif($role === 'employee') bg-blue-100 text-blue-800 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-200 dark:border-blue-800
                        @elseif($role === 'admin') bg-red-100 text-red-800 border border-red-200 dark:bg-red-900/40 dark:text-red-200 dark:border-red-800
                        @else bg-gray-100 text-gray-800 border border-gray-200 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600 @endif">
                        {{ ucfirst($role) }}
                    </span>
                </div>

                <div class="flex-1"></div>

                {{-- Right side --}}
                <div class="flex items-center gap-2 flex-shrink-0">

                    {{-- ✅ Dark Mode Toggle — clean icon-only --}}
                    <button type="button"
                        class="hs-dark-mode-active:hidden hs-dark-mode p-1.5 rounded-md text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700/50 transition"
                        data-hs-theme-click-value="dark" aria-label="Enable dark mode">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg>
                    </button>
                    <button type="button"
                        class="hs-dark-mode-active:block hidden hs-dark-mode p-1.5 rounded-md text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700/50 transition"
                        data-hs-theme-click-value="light" aria-label="Enable light mode">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2" />
                            <path d="M12 20v2" />
                            <path d="m4.93 4.93 1.41 1.41" />
                            <path d="m17.66 17.66 1.41 1.41" />
                            <path d="M2 12h2" />
                            <path d="M20 12h2" />
                            <path d="m6.34 17.66-1.41 1.41" />
                            <path d="m19.07 4.93-1.41 1.41" />
                        </svg>
                    </button>

                    @if($role === 'customer')
                    <a href="{{ route('livewire.customer.cart') }}"
                        class="relative p-2 text-gray-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 transition">
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
                    @livewire('components.notification-bell', ['context' => 'customer'])
                    @elseif($role === 'owner')
                    @livewire('components.notification-bell', ['context' => 'owner'])
                    @elseif($role === 'employee')
                    @livewire('components.notification-bell', ['context' => 'employee'])
                    @elseif($role === 'admin')
                    @livewire('components.notification-bell', ['context' => 'admin'], key('admin-notif'))
                    @endif

                    {{-- Profile dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-2 p-1.5 flex-shrink-0 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                            @php
                            $user = auth()->user();
                            $profilePic = $user->profile_picture;
                            @endphp

                            @if($profilePic)
                            <img src="{{ asset('storage/' . $profilePic) }}?v={{ time() }}" alt="{{ $user->name }}"
                                class="w-8 h-8 flex-shrink-0 rounded-full object-cover border-2 border-gray-200 dark:border-slate-600">
                            @else
                            <div
                                class="w-8 h-8 flex-shrink-0 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                            </div>
                            @endif

                            <svg class="w-4 h-4 text-gray-500 dark:text-slate-400 hidden sm:block" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-cloak
                            class="absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 py-1 z-50">
                            {{ $dropdown }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ==================== PAGE CONTENT ==================== --}}
        <main class="px-4 sm:px-6 py-6">
            {{ $slot }}
        </main>
    </div>
</div>
