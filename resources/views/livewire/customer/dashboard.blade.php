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
            <h2 class="text-xl font-semibold text-gray-900"> Featured Bakeshops</h2>
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
    @elseif(empty($search) || $products->count() === 0)
    {{-- ✅ Empty state: only show if there's no search OR no products found either --}}
    <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
        <span class="text-6xl block mb-4">🏪</span>
        @if(!empty($search))
        <p class="text-gray-500 text-lg">No bakeshops or products found matching "<span
                class="font-medium text-amber-600">{{
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

    <!-- ✅ Product Search Results -->
    @if(!empty($search) && $products->count() > 0)
    <div class="mb-8" wire:loading.class="opacity-50" wire:target="search">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">🛒 Products</h2>
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
                        class="w-full h-full flex items-center justify-center text-3xl bg-gradient-to-br from-amber-50 to-orange-50">
                        🍰
                    </div>
                    @endif
                </div>
                <div class="p-3 flex-1 flex flex-col">
                    <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">
                        🏪 {{ $product->shop->shop_name ?? 'Unknown Shop' }}
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
                        @if($totalStock <= 5) <span class="text-[10px] text-orange-500 font-medium">⚠️ Only {{
                            $totalStock }} left</span>
                            @else
                            <span class="text-[10px] text-green-500">✅ In stock</span>
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