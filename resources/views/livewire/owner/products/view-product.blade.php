<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        <!-- FLASH MESSAGE -->
        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
        </div>
        @endif

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📦 Products</h1>
                <p class="text-sm text-gray-500">View product listings and details</p>
                @if($selectedBranchId)
                @php
                $branch = auth()->user()->shop->branches->firstWhere('id', $selectedBranchId);
                @endphp
                @if($branch)
                <p class="text-sm text-amber-600 mt-1">
                    Showing products for: <span class="font-medium">{{ $branch->name }}</span>
                </p>
                @endif
                @endif
                @if($showDeleted)
                <p class="text-sm text-red-600 mt-1">Showing deleted products</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="showCreateForm"
                    class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                    + Add Product
                </button>
                <button wire:click="toggleDeleted"
                    class="px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                    {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                </button>
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    ← Dashboard
                </a>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search products by name or description..."
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
                <span class="text-gray-400">({{ $products->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- PRODUCT FORM -->
        @if($showForm)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $editing ? '✏️ Edit Product' : '➕ Add New Product' }}
            </h2>
            <form wire:submit.prevent="saveProduct" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" wire:model="price"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('price')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select wire:model="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select wire:model="form_branch_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('form_branch_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Initial Stock</label>
                    <input type="number" wire:model="stock"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('stock')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                    <input type="file" wire:model.live="image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image Preview</label>
                    <div
                        class="w-48 h-48 bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm mx-auto">
                        @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif($image_url)
                        <img src="{{ $image_url }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <span class="text-4xl">🖼️</span>
                            <p class="text-sm font-medium text-gray-500">No image selected</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2 flex gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        {{ $editing ? 'Update Product' : 'Save Product' }}
                    </button>
                    <button type="button" wire:click="cancelForm"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- PRODUCTS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($products as $product)
            @php
            $orderItems = \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($q) {
            $q->where('status', 'completed');
            })
            ->get();
            $totalSold = $orderItems->sum('quantity');
            $totalRevenue = $orderItems->sum(function($item) {
            return $item->quantity * $item->price;
            });
            $stock = $product->current_stock ?? 0;
            @endphp

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col h-full {{ $product->trashed() ? 'opacity-75 border-red-200' : '' }}">

                <!-- Image -->
                <div class="h-48 bg-gray-100 overflow-hidden flex-shrink-0">
                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" class="w-full h-full object-cover">
                    @else
                    <div
                        class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                        <span class="text-5xl mb-2">🍰</span>
                        <span class="text-sm">No Image</span>
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-gray-800 text-lg leading-tight">{{ $product->name }}</h3>
                        @if($product->trashed())
                        <span
                            class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full flex-shrink-0">Deleted</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-500 mt-0.5">{{ $product->category->name ?? 'No Category' }}</p>

                    <p class="text-lg font-bold text-amber-600 mt-1">₱{{ number_format($product->price, 2) }}</p>

                    <!-- Stock Display -->
                    <div class="mt-1">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full
                            {{ $stock > 10 ? 'bg-green-100 text-green-800' : '' }}
                            {{ $stock <= 10 && $stock > 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $stock <= 0 ? 'bg-red-100 text-red-800' : '' }}">
                            📦 {{ $stock }} in stock
                        </span>
                    </div>

                    <!-- Sales Summary -->
                    <div class="mt-2 flex items-center gap-3 flex-wrap">
                        <span class="text-xs text-gray-500">📊 Sold:</span>
                        <span class="text-xs font-semibold text-green-600">{{ $totalSold }}</span>
                        <span class="text-xs text-gray-300">|</span>
                        <span class="text-xs font-semibold text-amber-600">₱{{ number_format($totalRevenue, 2) }}</span>
                    </div>

                    <!-- Branches -->
                    <div class="mt-3">
                        <p class="text-xs font-medium text-gray-700">Available at:</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($product->branches as $branch)
                            <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                {{ $branch->name }}
                            </span>
                            @empty
                            <span class="text-xs text-gray-400">No branches assigned</span>
                            @endforelse
                        </div>
                    </div>

                    @if($product->description)
                    <p class="text-sm text-gray-600 mt-3 line-clamp-2 flex-1">{{ $product->description }}</p>
                    @endif
                </div>

                <!-- Actions -->
                <div class="border-t border-gray-100 flex-shrink-0">
                    @if($product->trashed())
                    <div class="flex divide-x divide-gray-200">
                        <button wire:click="restore({{ $product->id }})"
                            class="flex-1 py-3 text-sm font-medium text-green-600 hover:bg-green-50 transition">
                            🔄 Restore
                        </button>
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Permanently delete this product?') || event.stopImmediatePropagation()"
                            class="flex-1 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            🗑️ Delete
                        </button>
                    </div>
                    @else
                    <div class="flex divide-x divide-gray-200">
                        <button wire:click="viewProductDetails({{ $product->id }})"
                            class="flex-1 py-3 text-sm font-medium text-blue-600 hover:bg-blue-50 transition">
                            📊 View Details
                        </button>
                        <button wire:click="editProduct({{ $product->id }})"
                            class="flex-1 py-3 text-sm font-medium text-amber-600 hover:bg-amber-50 transition">
                            ✏️ Edit
                        </button>
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Delete this product?') || event.stopImmediatePropagation()"
                            class="flex-1 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            🗑️ Delete
                        </button>
                    </div>
                    @endif
                </div>

            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <span class="text-6xl block mb-4">📭</span>
                <p class="text-gray-500 text-lg">
                    @if(!empty($search))
                    No products found matching "<span class="font-medium text-amber-600">{{ $search }}</span>"
                    @else
                    No products found
                    @endif
                </p>
                <p class="text-sm text-gray-400">
                    @if(!empty($search))
                    Try adjusting your search or filters.
                    @else
                    Try adjusting your filters or check back later.
                    @endif
                </p>
            </div>
            @endforelse
        </div>

    </div>

    <!-- Product Details Modal -->
    @if($showProductModal && $selectedProduct)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="overscroll-behavior: contain;">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm" wire:click="closeProductModal"></div>

        <div class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🍰</span>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">📦 Product Details</h3>
                            <p class="text-sm text-gray-500">{{ $selectedProduct->name }}</p>
                        </div>
                    </div>
                    <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <!-- Product Image & Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                        @if($selectedProduct->image_url)
                        <img src="{{ asset($selectedProduct->image_url) }}" class="w-full h-full object-cover">
                        @else
                        <div
                            class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                            <span class="text-5xl mb-2">🍰</span>
                            <span class="text-sm">No Image</span>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold text-gray-800">{{ $selectedProduct->name }}</h2>
                        <p class="text-sm text-gray-500">📂 {{ $selectedProduct->category->name ?? 'Uncategorized' }}
                        </p>
                        <p class="text-2xl font-bold text-amber-600">₱{{ number_format($selectedProduct->price, 2) }}
                        </p>
                        @php
                        $totalStock = $selectedProduct->branches->sum('pivot.stock');
                        @endphp
                        <p class="text-sm text-gray-600">📦 Stock: <span class="font-medium">{{ $totalStock }}</span>
                            units available</p>
                        @if($selectedProduct->description)
                        <p class="text-sm text-gray-600 mt-2 border-t border-gray-100 pt-2">{{
                            $selectedProduct->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Analytics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Sold</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $productAnalytics['total_sold'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Orders</p>
                        <p class="text-2xl font-bold text-green-600">{{ $productAnalytics['total_orders'] ?? 0 }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Revenue</p>
                        <p class="text-2xl font-bold text-amber-600">₱{{
                            number_format($productAnalytics['total_revenue'] ?? 0, 2) }}</p>
                    </div>
                </div>

                <!-- Stock by Branch -->
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">📍 Stock by Branch</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forelse($selectedProduct->branches as $branch)
                        <div class="border border-gray-100 rounded-lg p-3 bg-gray-50 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">{{ $branch->name }}</span>
                            <span
                                class="text-sm font-semibold {{ $branch->pivot->stock > 5 ? 'text-green-600' : ($branch->pivot->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $branch->pivot->stock }}
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 col-span-2">No branches assigned.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="border-t border-gray-200 pt-4">
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
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeProductModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>