<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-amber-50 to-white rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-gray-600 mt-2">Where every bread, cake, and pastry is made fresh with love and quality
            ingredients.</p>
        <a href="{{ route('livewire.customer.browse-shops') }}"
            class="inline-flex items-center mt-4 px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
            Explore Bakeshops →
        </a>
    </div>

    <!-- Start Selling Button -->
    @if($canApplyAsSeller)
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-4xl">🚀</span>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Start Selling!</h3>
                    <p class="text-sm text-gray-600">Turn your passion into business. Register your bakeshop today.</p>
                </div>
            </div>
            <a href="{{ route('livewire.customer.start-selling') }}"
                class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-md hover:shadow-lg whitespace-nowrap">
                Start Selling →
            </a>
        </div>
    </div>
    @elseif($hasActiveShop)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-4xl">🏪</span>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">You're already selling!</h3>
                    <p class="text-sm text-gray-600">Manage your shop, products, and orders.</p>
                </div>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="px-6 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition shadow-md hover:shadow-lg whitespace-nowrap">
                Go to Shop Dashboard →
            </a>
        </div>
    </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" wire:model.live="search"
                placeholder="Search bakeshops by name, address, or description..."
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
            <span class="text-gray-400">({{ $featuredShops->count() }} found)</span>
        </p>
        @endif
    </div>

    <!-- Featured Shops -->
    @if($featuredShops->count() > 0)
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">🏪 Featured Bakeshops</h2>
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                View All →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredShops as $shop)
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div
                    class="h-32 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-4xl">🍰</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900">{{ $shop->shop_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                    <a href="{{ route('livewire.customer.view-products', $shop->id) }}"
                        class="mt-3 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Visit Shop →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
        <span class="text-6xl block mb-4">🏪</span>
        @if(!empty($search))
        <p class="text-gray-500 text-lg">No bakeshops found matching "<span class="font-medium text-amber-600">{{
                $search }}</span>"</p>
        <p class="text-sm text-gray-400">Try adjusting your search or explore all shops.</p>
        <a href="{{ route('livewire.customer.browse-shops') }}"
            class="inline-block mt-4 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
            Browse All Shops
        </a>
        @else
        <p class="text-gray-500 text-lg">No bakeshops available yet</p>
        <p class="text-sm text-gray-400">Check back later for new bakeshops.</p>
        @endif
    </div>
    @endif

    <!-- Features -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">🏪</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Multiple Shops</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">📍</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Easy Pickup</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">🥖</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Fresh Daily</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">💳</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Secure Payments</p>
        </div>
    </div>
</div>