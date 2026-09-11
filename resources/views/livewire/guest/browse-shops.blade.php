<div>
    <div class="max-w-[85rem] px-4 py-6 sm:px-6 sm:py-10 lg:px-8 lg:py-14 mx-auto">

        <!-- ✅ HERO SECTION FOR GUESTS -->
        <div
            class="bg-gradient-to-br from-amber-50 to-white rounded-xl p-5 sm:p-8 mb-6 sm:mb-8 border border-amber-100">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome to Web-based Multi-tenant Bakeshop 🍰</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Discover delicious baked goods from the best bakeshops in Victorias City.
                Every bread, cake, and pastry is made fresh with love and quality ingredients.
            </p>
            <div class="flex flex-wrap items-center gap-4 mt-4">
                <a href="{{ route('livewire.guest.start-selling') }}"
                    class="inline-flex items-center px-5 py-2.5 sm:px-6 sm:py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
                    🚀 Start Selling
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative" style="height: 2.5rem;">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search bakeshops by name, address, or description..."
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
                <span class="text-gray-400">({{ $shops->count() }} found)</span>
            </p>
            @endif
        </div>

        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 text-center">Featured Bakeshops</h2>

        <!-- GRID - 2 columns on medium screens -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @forelse($shops as $shop)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <!-- Shop Header -->
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 bg-amber-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($shop->shop_image)
                            <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                                class="w-full h-full object-cover">
                            @else
                            <span class="text-3xl">🏪</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-base sm:text-lg break-words">{{ $shop->shop_name
                                }}</h3>
                            <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                            <p class="text-sm text-gray-400">👤 {{ $shop->user->name ?? 'N/A' }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                @if(($shop->rating_count ?? 0) > 0)
                                <span class="text-amber-500 text-sm">⭐</span>
                                <span class="font-semibold text-gray-800 text-sm">{{ number_format($shop->rating ?? 0,
                                    1) }}</span>
                                <span class="text-sm text-gray-500">({{ $shop->rating_count ?? 0 }} reviews)</span>
                                @else
                                <span class="text-sm text-gray-400">No reviews yet</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                📍 {{ $shop->branches->pluck('name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="p-4">
                    @php
                    $products = $shopProducts[$shop->id] ?? collect();
                    @endphp

                    @if($products && $products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                        @foreach($products as $product)
                        <div
                            class="bg-gray-50 rounded-lg p-2 sm:p-3 text-center hover:shadow-sm transition border border-transparent hover:border-amber-200">
                            @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}"
                                class="w-full h-20 sm:h-16 object-cover rounded-lg mb-1">
                            @else
                            <div
                                class="w-full h-20 sm:h-16 bg-amber-100 rounded-lg flex items-center justify-center text-2xl mb-1">
                                🍰
                            </div>
                            @endif
                            <p class="text-xs font-medium text-gray-800 truncate">{{ $product->name }}</p>
                            <p class="text-xs font-bold text-amber-600">₱{{ number_format($product->price, 2) }}</p>
                            @if($product->product_reviews_avg_rating > 0)
                            <div class="flex items-center justify-center gap-1 text-xs text-gray-500">
                                <span class="text-amber-500">⭐</span>
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
                            class="inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                            View All Products →
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-1 md:col-span-2 text-center py-12 text-gray-500">
                @if(!empty($search))
                <p class="text-lg">No bakeshops found matching "<span class="font-medium text-amber-600">{{ $search
                        }}</span>"</p>
                <p class="text-sm text-gray-400">Try adjusting your search.</p>
                @else
                <p class="text-lg">No bakeshops available yet.</p>
                @endif
            </div>
            @endforelse
        </div>

    </div>
</div>