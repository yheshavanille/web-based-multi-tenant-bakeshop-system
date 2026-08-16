<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                ← Back to Shops
            </a>
        </div>

        <!-- Shop Info -->
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
                </div>
            </div>
        </div>

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
                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition hover:border-amber-200">
                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                    @endif
                    <h3 class="font-semibold text-gray-800 text-lg">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>
                    <p class="font-semibold text-amber-600 text-lg mt-1">₱{{ number_format($product->price, 2) }}</p>

                    <!-- STOCK INFORMATION -->
                    <div class="mt-2">
                        @if($stock <= 0) <p class="text-xs text-red-500 font-medium">🚫 Out of Stock</p>
                            @elseif($stock <= 5) <p class="text-xs text-orange-500 font-medium">⚠️ Only {{ $stock }}
                                left!</p>
                                @else
                                <p class="text-xs text-green-500">✅ {{ $stock }} in stock</p>
                                @endif
                    </div>

                    <!-- BRANCH AVAILABILITY -->
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

                    <!-- Add to Cart Button -->
                    @if($stock > 0)
                    <button wire:click="addToCart({{ $product->id }})"
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
</div>