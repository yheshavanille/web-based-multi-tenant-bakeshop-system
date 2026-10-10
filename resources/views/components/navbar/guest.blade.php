<nav class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between gap-2 py-2 sm:h-16 sm:py-0">
            <!-- Logo -->
            <div class="min-w-0 flex-1">
                <a href="{{ route('livewire.guest.browse-shops') }}" class="flex min-w-0 items-center gap-2">
                    <span
                        class="min-w-0 break-words text-lg font-bold leading-tight text-gray-900 dark:text-slate-100 sm:text-xl">Web-based
                        Multi-Tenant Bakeshop System</span>
                </a>
            </div>

            <!-- Right Side - Dark Mode + Login/Register -->
            <div class="flex shrink-0 items-center gap-1 sm:gap-3">

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

                <a href="{{ route('livewire.auth.login') }}"
                    class="px-2 py-2 text-xs font-medium text-gray-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition sm:px-4 sm:text-sm">
                    Log In
                </a>
                <a href="{{ route('livewire.auth.register') }}"
                    class="px-3 py-2 text-xs font-medium bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition shadow-sm hover:shadow-md sm:px-4 sm:text-sm">
                    Sign Up
                </a>
            </div>
        </div>
    </div>
</nav>