<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between gap-2 py-2 sm:h-16 sm:py-0">
            <!-- Logo -->
            <div class="min-w-0 flex-1">
                <a href="{{ route('livewire.guest.browse-shops') }}" class="flex min-w-0 items-center gap-2">
                    <span class="text-xl sm:text-2xl">🍞</span>
                    <span class="min-w-0 break-words text-lg font-bold leading-tight text-gray-900 sm:text-xl">Web-based
                        Multi-Tenant Bakeshop System</span>
                </a>
            </div>

            <!-- Right Side - Login/Register -->
            <div class="flex shrink-0 items-center gap-1 sm:gap-3">
                <a href="{{ route('livewire.auth.login') }}"
                    class="px-2 py-2 text-xs font-medium text-gray-700 hover:text-amber-600 transition sm:px-4 sm:text-sm">
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