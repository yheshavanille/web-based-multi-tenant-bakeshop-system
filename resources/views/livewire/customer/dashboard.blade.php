<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-amber-50 to-white rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h1>
        <p class="text-gray-600 mt-2">Where every bread, cake, and pastry is made fresh with love and quality
            ingredients.</p>
        <a href="{{ route('livewire.customer.browse-shops') }}"
            class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
            Explore Bakeshops
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                </path>
            </svg>
        </a>
    </div>

    <!-- Start Selling Button -->
    @if($canApplyAsSeller)
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Start Selling!</h3>
                    <p class="text-sm text-gray-600">Turn your passion into business. Register your bakeshop today.</p>
                </div>
            </div>
            <a href="{{ route('livewire.customer.start-selling') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-md hover:shadow-lg whitespace-nowrap">
                Start Selling
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>
    @elseif($hasActiveShop)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">You're already selling!</h3>
                    <p class="text-sm text-gray-600">Manage your shop, products, and orders.</p>
                </div>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition shadow-md hover:shadow-lg whitespace-nowrap">
                Go to Shop Dashboard
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-4">
        <div class="relative" style="height: 2.5rem;">
            <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                style="position: absolute;">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search bakeshops or products..."
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
            <span class="text-gray-400">({{ $featuredShops->count() }} shops, {{ $products->count() }} products
                found)</span>
        </p>
        @endif
    </div>

    <!-- Featured Shops -->
    @if($featuredShops->count() > 0)
    <div class="mb-8" wire:loading.class="opacity-50" wire:target="search">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Featured Bakeshops</h2>
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-700 text-sm font-medium">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
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
                    <svg class="w-14 h-14 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900">{{ $shop->shop_name }}</h3>
                    <p class="text-sm text-gray-500 inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $shop->address ?? 'Victorias City' }}
                    </p>
                    <a href="{{ route('livewire.customer.view-products', $shop->id) }}"
                        class="mt-3 inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Visit Shop
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif(empty($search) || $products->count() === 0)
    {{-- Empty state --}}
    <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </div>
        @if(!empty($search))
        <p class="text-gray-500 text-lg">No bakeshops or products found matching "<span
                class="font-medium text-amber-600">{{ $search }}</span>"</p>
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

    <!-- Product Search Results -->
    @if(!empty($search) && $products->count() > 0)
    <div class="mb-8" wire:loading.class="opacity-50" wire:target="search">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 inline-flex items-center gap-2">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            Products
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($products as $product)
            @php
            $displayPrice = $product->isDiscounted()
            ? $product->getDiscountedPrice()
            : $product->price;
            $totalStock = $product->branches->sum('pivot.stock');
            @endphp
            <a href="{{ route('livewire.customer.view-products', ['shopId' => $product->shop_id]) }}#product-{{ $product->id }}"
                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-amber-300 transition flex flex-col">
                <div class="w-full h-32 bg-gray-100 overflow-hidden flex-shrink-0">
                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover">
                    @else
                    <div
                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-50 to-orange-50">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-3 flex-1 flex flex-col">
                    <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5 truncate inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        {{ $product->shop->shop_name ?? 'Unknown Shop' }}
                    </p>
                    <div class="mt-2">
                        @if($product->isDiscounted())
                        <span class="text-xs text-gray-400 line-through">
                            ₱{{ number_format($product->price, 2) }}
                        </span>
                        <span class="text-base font-bold text-green-600 ml-1">
                            ₱{{ number_format($displayPrice, 2) }}
                        </span>
                        @else
                        <span class="text-base font-bold text-amber-600">
                            ₱{{ number_format($displayPrice, 2) }}
                        </span>
                        @endif
                    </div>
                    <div class="mt-1">
                        @if($totalStock <= 5) <span
                            class="inline-flex items-center gap-1 text-[10px] text-orange-500 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Only {{ $totalStock }} left
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[10px] text-green-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                In stock
                            </span>
                            @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Features -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Multiple Shops</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Easy Pickup</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Fresh Daily</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Secure Payments</p>
        </div>
    </div>
</div>