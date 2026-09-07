<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-4">
                <a href="{{ route('livewire.guest.browse-shops') }}" class="flex items-center gap-2">
                    <span class="text-2xl">🍞</span>
                    <span class="text-xl font-bold text-gray-900">Web-based Multi-Tenant Bakeshop System</span>
                </a>
            </div>

            <!-- Right Side - Login/Register -->
            <div class="flex items-center gap-3">
                <a href="{{ route('livewire.auth.login') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-amber-600 transition">
                    Log In
                </a>
                <a href="{{ route('livewire.auth.register') }}"
                    class="px-4 py-2 text-sm font-medium bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition shadow-sm hover:shadow-md">
                    Sign Up
                </a>
            </div>
        </div>
    </div>
</nav>