<div x-data="productScroller()" x-init="init()">
    {{-- ✅ Auto-scroll to the product if URL has #product-{id} --}}
    <script>
        function productScroller() {
            return {
                init() {
                    const scrollToHash = (attempt = 0) => {
                        const hash = window.location.hash;
                        if (!hash || !hash.startsWith('#product-')) return;

                        const el = document.querySelector(hash);

                        if (!el) {
                            if (attempt < 5) {
                                setTimeout(() => scrollToHash(attempt + 1), 300);
                            }
                            return;
                        }

                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        el.classList.add('ring-4', 'ring-amber-400', 'ring-offset-2');
                        setTimeout(() => {
                            el.classList.remove('ring-4', 'ring-amber-400', 'ring-offset-2');
                        }, 2500);

                        history.replaceState(null, '', window.location.pathname + window.location.search);
                    };

                    setTimeout(() => scrollToHash(0), 400);

                    document.addEventListener('livewire:navigated', () => {
                        setTimeout(() => scrollToHash(0), 400);
                    });
                }
            }
        }
    </script>

    <style>
        @media (max-width: 639px) {
            .guest-products-content {
                padding-top: 5rem;
            }
        }
    </style>

    <div class="guest-products-content max-w-6xl mx-auto px-4 sm:px-6 py-10">

        <!-- Back Button with Background -->
        <div class="mb-6">
            <a href="{{ route('livewire.guest.browse-shops') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm font-medium shadow-sm hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Shops
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search products by name or description..."
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
                <span class="text-gray-400">({{ $products->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Shop Info with Rating -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-20 h-20 bg-amber-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" class="w-full h-full object-cover">
                    @else
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $shop->shop_name }}</h1>
                    <p class="text-sm text-gray-500 inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $shop->address ?? 'Victorias City' }}
                    </p>
                    <p class="text-sm text-gray-400 inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $shop->user->name ?? 'N/A' }}
                    </p>
                    @if($shopRatingCount > 0)
                    <div class="flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        <span class="font-semibold text-gray-800 text-sm">{{ number_format($shopRating, 1) }}</span>
                        <span class="text-sm text-gray-500">({{ $shopRatingCount }} reviews)</span>
                    </div>
                    @else
                    <p class="text-sm text-gray-400 mt-1">No reviews yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Best Sellers Section -->
        @if($bestSellers->count() > 0 && $selectedBranchId)
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl shadow-sm border border-amber-200 p-5 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z">
                    </path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Best Sellers</h2>
                <span class="text-xs text-gray-500 ml-auto">
                    {{ $branches->firstWhere('id', $selectedBranchId)?->name ?? '' }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($bestSellers as $item)
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">
                    <div class="w-full h-32 bg-gray-100 overflow-hidden">
                        @if($item->product && $item->product->image_url)
                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
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
                    <div class="p-3">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $item->product?->name ?? 'Product
                            Unavailable' }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-gray-500">{{ $item->total_sold }} sold</span>
                            <span class="text-xs font-bold text-amber-600">₱{{ number_format($item->total_revenue, 2)
                                }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Branch Cards -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Select Branch
            </h2>
            @if($branches->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($branches as $branch)
                <button wire:click="selectBranch({{ $branch->id }})"
                    class="block w-full text-left bg-white rounded-2xl shadow-sm border {{ $selectedBranchId == $branch->id ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300' }} p-5 transition hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $branch->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $branch->address }}</p>
                        </div>
                        @if($selectedBranchId == $branch->id)
                        <span class="inline-flex items-center gap-1 text-amber-500 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Selected
                        </span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center gap-3 text-sm text-gray-500">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $branch->products()->wherePivot('stock', '>', 0)->count() }} products
                        </span>
                    </div>
                    @if($selectedBranchId == $branch->id)
                    <div class="mt-2 text-xs text-amber-600">Viewing this branch</div>
                    @endif
                </button>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500 bg-white rounded-2xl border border-gray-200">
                <p>No branches available for this shop.</p>
            </div>
            @endif
        </div>

        <!-- Products Section -->
        @if($selectedBranchId)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 inline-flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Products
                    </h2>
                    @php
                    $selectedBranch = $branches->firstWhere('id', $selectedBranchId);
                    @endphp
                    @if($selectedBranch)
                    <p class="text-sm text-gray-500">
                        Showing products available at
                        <span class="font-medium text-amber-600">{{ $selectedBranch->name }}</span>
                    </p>
                    @endif
                </div>
                <div>
                    <select wire:model.live="selectedCategory"
                        class="w-full sm:w-48 px-3 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm appearance-none bg-white bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right:10px_center] bg-no-repeat pr-10">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($products as $product)
                @php
                $stock = $product->branches->firstWhere('id', $selectedBranchId)?->pivot->stock ?? 0;
                @endphp
                <div id="product-{{ $product->id }}"
                    class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition hover:border-amber-200 cursor-pointer flex flex-col transition-all duration-500"
                    wire:click="openReviewModal({{ $product->id }})">

                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                    @endif

                    <h3 class="font-semibold text-gray-800 text-lg">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>

                    @if($product->isDiscounted())
                    <div class="mt-1">
                        <span class="text-sm text-gray-400 line-through">₱{{ number_format($product->price, 2) }}</span>
                        <span class="text-lg font-bold text-green-600 ml-2">₱{{
                            number_format($product->getDiscountedPrice(), 2) }}</span>
                        <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full ml-2">{{
                            $product->getDiscountLabel() }}</span>
                    </div>
                    @else
                    <p class="text-lg font-bold text-amber-600 mt-1">₱{{ number_format($product->price, 2) }}</p>
                    @endif

                    <div class="flex items-center gap-1 mt-1">
                        @if($product->product_reviews_avg_rating > 0)
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{
                            number_format($product->product_reviews_avg_rating, 1) }}</span>
                        <span class="text-xs text-gray-400">({{ $product->productReviews->count() }} reviews)</span>
                        @else
                        <span class="text-xs text-gray-400">No ratings yet</span>
                        @endif
                    </div>

                    <div class="mt-2">
                        @if($stock <= 0) <p class="inline-flex items-center gap-1 text-xs text-red-500 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                            Out of Stock
                            </p>
                            @elseif($stock <= 5) <p
                                class="inline-flex items-center gap-1 text-xs text-orange-500 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                Only {{ $stock }} left!
                                </p>
                                @else
                                <p class="inline-flex items-center gap-1 text-xs text-green-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $stock }} in stock
                                </p>
                                @endif
                    </div>

                    <div class="mt-2">
                        <p class="text-xs font-medium text-gray-700">Available for pickup at:</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($product->branches as $branch)
                            <span
                                class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $branch->name }}
                            </span>
                            @empty
                            <span class="text-xs text-gray-400">No branches available</span>
                            @endforelse
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 line-clamp-2 mt-2 flex-1">{{ $product->description }}</p>

                    <div class="mt-auto pt-3">
                        @if($stock > 0)
                        <button wire:click.stop="addToCart({{ $product->id }})"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Add to Cart
                        </button>
                        @else
                        <button disabled
                            class="w-full px-4 py-2.5 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed text-sm font-medium">
                            Out of Stock
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    @if(!empty($search))
                    <p>No products found matching "<span class="font-medium text-amber-600">{{ $search }}</span>"</p>
                    <p class="text-xs text-gray-400">Try adjusting your search.</p>
                    @else
                    <p>No products available at this branch.</p>
                    @endif
                </div>
                @endforelse
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
            <p class="text-lg">Please select a branch to view products.</p>
        </div>
        @endif
    </div>

    <!-- ✅ PRODUCT DETAILS + REVIEWS MODAL -->
    @if($showReviewModal && $selectedProduct)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="overscroll-behavior: contain;">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeReviewModal"></div>

        <div class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Product Details</h3>
                        <p class="text-sm text-gray-500">{{ $selectedProduct->name }}</p>
                    </div>
                    <button wire:click="closeReviewModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                @if($selectedProduct->image_url)
                <div class="w-full h-64 rounded-xl overflow-hidden bg-gray-100">
                    <img src="{{ asset($selectedProduct->image_url) }}" alt="{{ $selectedProduct->name }}"
                        class="w-full h-full object-cover">
                </div>
                @endif

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $selectedProduct->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $selectedProduct->category->name ?? 'No Category' }}</p>

                    @if($selectedProduct->isDiscounted())
                    <div>
                        <span class="text-sm text-gray-400 line-through">₱{{ number_format($selectedProduct->price, 2)
                            }}</span>
                        <span class="text-2xl font-bold text-green-600 ml-2">₱{{
                            number_format($selectedProduct->getDiscountedPrice(), 2) }}</span>
                        <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full ml-2">{{
                            $selectedProduct->getDiscountLabel() }}</span>
                    </div>
                    @else
                    <p class="text-2xl font-bold text-amber-600 mt-2">₱{{ number_format($selectedProduct->price, 2) }}
                    </p>
                    @endif

                    <div class="flex items-center gap-2 mt-2">
                        @if($averageRating > 0)
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        <span class="font-semibold text-gray-800 text-lg">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-sm text-gray-500">({{ $selectedProduct->productReviews->count() }}
                            reviews)</span>
                        @else
                        <span class="text-sm text-gray-400">No ratings yet</span>
                        @endif
                    </div>
                </div>

                <!-- Total Sold -->
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Total Sold</p>
                            <p class="text-lg font-bold text-amber-600">
                                @php
                                $branchId = $selectedBranchId ?? 0;
                                $soldCount = \App\Models\OrderItem::whereHas('order', function($q) use ($branchId) {
                                $q->where('branch_id', $branchId)
                                ->where('status', 'completed');
                                })
                                ->where('product_id', $selectedProduct->id)
                                ->sum('quantity');
                                @endphp
                                {{ $soldCount }} units
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($selectedProduct->description)
                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h10M4 18h10"></path>
                        </svg>
                        Description
                    </h4>
                    <p class="text-sm text-gray-600">{{ $selectedProduct->description }}</p>
                </div>
                @endif

                <!-- Stock -->
                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Stock Availability
                    </h4>
                    @php
                    $stock = $selectedProduct->branches->firstWhere('id', $selectedBranchId)?->pivot->stock ?? 0;
                    @endphp
                    <div class="flex items-center gap-2">
                        @if($stock > 0)
                        <span class="inline-flex items-center gap-1 text-sm text-green-600 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $stock }} in stock
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-sm text-red-600 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                            Out of Stock
                        </span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-gray-700">Available for pickup at:</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($selectedProduct->branches as $branch)
                            <span
                                class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $branch->name }}
                            </span>
                            @empty
                            <span class="text-xs text-gray-400">No branches available</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700 inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                            Customer Reviews
                        </h4>
                        <span class="text-xs text-gray-500">{{ $selectedProduct->productReviews->count() }}
                            reviews</span>
                    </div>

                    @if($selectedProduct->productReviews->count() > 0)
                    <div class="space-y-4 max-h-60 overflow-y-auto pr-2">
                        @foreach($selectedProduct->productReviews as $review)
                        <div class="border-b border-gray-100 pb-3 last:border-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $review->customer->name ??
                                        'Anonymous' }}</p>
                                    <div class="flex items-center gap-0.5 text-amber-500">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>
                                            @endfor
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->review)
                            <p class="text-sm text-gray-600 mt-1">{{ $review->review }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-gray-500">
                        <p class="text-sm">No reviews yet for this product.</p>
                        <p class="text-xs">Be the first to leave a review!</p>
                    </div>
                    @endif
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end gap-3">
                @if($stock > 0)
                <button wire:click="addToCart({{ $selectedProduct->id }})" wire:click.stop
                    class="inline-flex items-center gap-2 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Add to Cart
                </button>
                @endif
                <button wire:click="closeReviewModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif

    <!-- ✅ LOGIN MODAL FOR GUESTS -->
    @if($showLoginModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeLoginModal"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800 inline-flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Login Required
                    </h3>
                    <button wire:click="closeLoginModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Please Login to Add Items to Your Cart</h4>
                <p class="text-sm text-gray-500 mb-6">
                    You need to be logged in to order from our bakeshops.
                </p>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('livewire.auth.login') }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                        Login Now
                    </a>
                    <a href="{{ route('livewire.auth.register') }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Create Account
                    </a>
                    <button wire:click="closeLoginModal"
                        class="w-full px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition">
                        Continue Browsing
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>