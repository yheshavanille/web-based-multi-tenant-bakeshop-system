<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                ← Back to Shops
            </a>
        </div>

        <!-- Shop Info with Rating -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" class="w-full h-full object-cover">
                    @else
                    <span class="text-3xl">🏪</span>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $shop->shop_name }}</h1>
                    <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                    <p class="text-sm text-gray-400">👤 {{ $shop->user->name ?? 'N/A' }}</p>
                    @if($shopRatingCount > 0)
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-amber-500 text-sm">⭐</span>
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
                <span class="text-2xl">🏆</span>
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
                            class="w-full h-full flex items-center justify-center text-4xl bg-gradient-to-br from-amber-50 to-orange-50">
                            🍰
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
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📍 Select Branch</h2>
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
                        <span class="text-amber-500 text-sm font-medium">✓ Selected</span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center gap-3 text-sm text-gray-500">
                        <span>📦 {{ $branch->products()->wherePivot('stock', '>', 0)->count() }} products</span>
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
                    <h2 class="text-lg font-semibold text-gray-800">📦 Products</h2>
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
                        class="w-full sm:w-48 px-3 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
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
                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition hover:border-amber-200 cursor-pointer"
                    wire:click="openReviewModal({{ $product->id }})">
                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                    @endif
                    <h3 class="font-semibold text-gray-800 text-lg">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>

                    <!-- ✅ DISPLAY DISCOUNTED PRICE -->
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
                        <span class="text-amber-500 text-sm">⭐</span>
                        <span class="text-sm font-medium text-gray-700">{{
                            number_format($product->product_reviews_avg_rating, 1) }}</span>
                        <span class="text-xs text-gray-400">({{ $product->productReviews->count() }} reviews)</span>
                        @else
                        <span class="text-xs text-gray-400">No ratings yet</span>
                        @endif
                    </div>

                    <div class="mt-2">
                        @if($stock <= 0) <p class="text-xs text-red-500 font-medium">🚫 Out of Stock</p>
                            @elseif($stock <= 5) <p class="text-xs text-orange-500 font-medium">⚠️ Only {{ $stock }}
                                left!</p>
                                @else
                                <p class="text-xs text-green-500">✅ {{ $stock }} in stock</p>
                                @endif
                    </div>

                    <div class="mt-2">
                        <p class="text-xs font-medium text-gray-700">Available for pickup at:</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($product->branches as $branch)
                            <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                📍 {{ $branch->name }}
                            </span>
                            @empty
                            <span class="text-xs text-gray-400">No branches available</span>
                            @endforelse
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 line-clamp-2 mt-2">{{ $product->description }}</p>

                    @if($stock > 0)
                    <button wire:click.stop="addToCart({{ $product->id }})"
                        class="mt-3 w-full px-4 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        Add to Cart 🛒
                    </button>
                    @else
                    <button disabled
                        class="mt-3 w-full px-4 py-2.5 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed text-sm font-medium">
                        Out of Stock
                    </button>
                    @endif
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    <p>No products available at this branch.</p>
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
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4"
        style="overscroll-behavior: contain;">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeReviewModal"></div>

        <div class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">📦 Product Details</h3>
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

                <!-- Product Image -->
                @if($selectedProduct->image_url)
                <div class="w-full h-64 rounded-xl overflow-hidden bg-gray-100">
                    <img src="{{ asset($selectedProduct->image_url) }}" alt="{{ $selectedProduct->name }}"
                        class="w-full h-full object-cover">
                </div>
                @endif

                <!-- Product Info -->
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
                        <span class="text-amber-500 text-lg">⭐</span>
                        <span class="font-semibold text-gray-800 text-lg">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-sm text-gray-500">({{ $selectedProduct->productReviews->count() }}
                            reviews)</span>
                        @else
                        <span class="text-sm text-gray-400">No ratings yet</span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($selectedProduct->description)
                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📝 Description</h4>
                    <p class="text-sm text-gray-600">{{ $selectedProduct->description }}</p>
                </div>
                @endif

                <!-- Stock -->
                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📦 Stock Availability</h4>
                    @php
                    $stock = $selectedProduct->branches->firstWhere('id', $selectedBranchId)?->pivot->stock ?? 0;
                    @endphp
                    <div class="flex items-center gap-2">
                        @if($stock > 0)
                        <span class="text-sm text-green-600 font-medium">✅ {{ $stock }} in stock</span>
                        @else
                        <span class="text-sm text-red-600 font-medium">🚫 Out of Stock</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <p class="text-xs font-medium text-gray-700">Available for pickup at:</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($selectedProduct->branches as $branch)
                            <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                📍 {{ $branch->name }}
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
                        <h4 class="text-sm font-semibold text-gray-700">⭐ Customer Reviews</h4>
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
                                    <div class="flex items-center gap-1 text-amber-500 text-sm">
                                        {{ str_repeat('⭐', $review->rating) }}
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
                    class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    Add to Cart 🛒
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
</div>