<div>
    <div class="max-w-[85rem] px-4 py-6 sm:px-6 sm:py-10 lg:px-8 lg:py-14 mx-auto">

        <!-- HERO SECTION FOR GUESTS -->
        <div
            class="bg-gradient-to-br from-amber-50 to-white rounded-xl p-5 sm:p-8 mb-6 sm:mb-8 border border-amber-100">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome to Web-based Multi-tenant Bakeshop</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Discover delicious baked goods from the best bakeshops in Victorias City.
                Every bread, cake, and pastry is made fresh with love and quality ingredients.
            </p>
            <div class="flex flex-wrap items-center gap-4 mt-4">
                <a href="{{ route('livewire.guest.start-selling') }}"
                    class="inline-flex items-center px-5 py-2.5 sm:px-6 sm:py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
                    Start Selling
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative" style="height: 2.5rem;">
                <div
                    class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search bakeshops or products..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition">
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
                <span class="text-gray-400">({{ $shops->count() }} shops, {{ $products->count() }} products
                    found)</span>
            </p>
            @endif
        </div>

        {{-- Featured Bakeshops section --}}
        @if($shops->count() > 0)

        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 text-center">Featured Bakeshops</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @foreach($shops as $shop)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 bg-amber-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($shop->shop_image)
                            <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                                class="w-full h-full object-cover">
                            @else
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-base sm:text-lg break-words">{{ $shop->shop_name
                                }}</h3>
                            <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                            <p class="text-sm text-gray-400 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $shop->user->name ?? 'N/A' }}
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                @if(($shop->rating_count ?? 0) > 0)
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                                <span class="font-semibold text-gray-800 text-sm">{{ number_format($shop->rating ?? 0,
                                    1) }}</span>
                                <span class="text-sm text-gray-500">({{ $shop->rating_count ?? 0 }} reviews)</span>
                                @else
                                <span class="text-sm text-gray-400">No reviews yet</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-1 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $shop->branches->pluck('name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    @php
                    $shopProductsList = $shopProducts[$shop->id] ?? collect();
                    @endphp

                    @if($shopProductsList && $shopProductsList->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                        @foreach($shopProductsList as $product)
                        <div
                            class="bg-gray-50 rounded-lg p-2 sm:p-3 text-center hover:shadow-sm transition border border-transparent hover:border-amber-200">
                            @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}"
                                class="w-full h-20 sm:h-16 object-cover rounded-lg mb-1">
                            @else
                            <div
                                class="w-full h-20 sm:h-16 bg-amber-100 rounded-lg flex items-center justify-center mb-1">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                                </svg>
                            </div>
                            @endif
                            <p class="text-xs font-medium text-gray-800 truncate">{{ $product->name }}</p>
                            <p class="text-xs font-bold text-amber-600">₱{{ number_format($product->price, 2) }}</p>
                            @if($product->product_reviews_avg_rating > 0)
                            <div class="flex items-center justify-center gap-1 text-xs text-gray-500">
                                <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                                <span>{{ number_format($product->product_reviews_avg_rating, 1) }}</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-gray-400 text-sm">
                        <p>No products available</p>
                    </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('livewire.guest.view-products', $shop->id) }}"
                            class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">
                            View All Products
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @elseif(empty($search))

        {{-- Initial page load — no shops exist at all --}}
        <div class="text-center py-12 text-gray-500">
            <p class="text-lg">No bakeshops available yet.</p>
        </div>

        @endif

        {{-- Product Search Results --}}
        @if(!empty($search) && $products->count() > 0)
        <div class="mt-8" wire:loading.class="opacity-50" wire:target="search">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 inline-flex items-center gap-2">
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
                <a href="{{ route('livewire.guest.view-products', ['shopId' => $product->shop_id]) }}#product-{{ $product->id }}"
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

        {{-- No results fallback --}}
        @if(!empty($search) && $shops->count() === 0 && $products->count() === 0)
        <div class="text-center py-12 text-gray-500">
            <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-lg">No bakeshops or products found matching "<span class="font-medium text-amber-600">{{
                    $search }}</span>"</p>
            <p class="text-sm text-gray-400 mt-1">Try adjusting your search.</p>
        </div>
        @endif

    </div>
</div>