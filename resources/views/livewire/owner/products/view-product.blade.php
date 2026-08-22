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
                    class="px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    ← Dashboard
                </a>
            </div>
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

                <!-- ✅ BRANCH SELECTION -->
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

                <!-- ✅ Image Preview - SQUARE SHAPE -->
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

                <!-- Discount Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                    <select wire:model.live="form_discount_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="none">No Discount</option>
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₱)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value</label>
                    <input type="number" step="0.01" min="0" wire:model.live="form_discount_value" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500
                        {{ $form_discount_type === 'none' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        placeholder="0.00" {{ $form_discount_type==='none' ? 'disabled' : '' }}>
                    @error('form_discount_value')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ✅ DISCOUNTED PRICE PREVIEW -->
                @if($form_discount_type !== 'none' && $form_discount_value > 0 && $price > 0)
                <div class="md:col-span-2 mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-gray-700">
                        💰 <span class="font-medium">Discounted Price:</span>
                        <span class="text-lg font-bold text-green-600">
                            ₱{{ number_format(
                            $form_discount_type === 'percentage'
                            ? $price * (1 - $form_discount_value / 100)
                            : max(0, $price - $form_discount_value),
                            2
                            ) }}
                        </span>
                        <span class="text-xs text-gray-500 line-through ml-2">
                            ₱{{ number_format($price, 2) }}
                        </span>
                        <span class="text-xs text-green-600 ml-2">
                            {{ $form_discount_type === 'percentage' ? $form_discount_value . '% OFF' : '₱' .
                            number_format($form_discount_value, 2) . ' OFF' }}
                        </span>
                    </p>
                </div>
                @endif

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
                <p class="text-gray-500 text-lg">No products found</p>
                <p class="text-sm text-gray-400">Try adjusting your filters or check back later.</p>
            </div>
            @endforelse

        </div>

    </div>

    <!-- Product Details Modal -->
    @if($showProductModal && $selectedProduct)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data
        x-init="document.body.classList.add('overflow-hidden')">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeProductModal">
        </div>

        <div class="relative w-full max-w-3xl overflow-hidden text-left transition-all transform bg-white rounded-2xl shadow-2xl flex flex-col"
            style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🍰</span>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $selectedProduct->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $selectedProduct->category->name ?? 'Uncategorized' }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 overflow-y-auto flex-1" style="max-height: 60vh;">

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
                        <p class="text-2xl font-bold text-amber-600">₱{{ number_format($selectedProduct->price, 2) }}
                        </p>
                        @if($selectedProduct->isDiscounted())
                        <p class="text-sm text-red-600">
                            ✅ Discount active: <span class="font-bold">{{ $selectedProduct->getDiscountLabel() }}</span>
                        </p>
                        <p class="text-lg font-bold text-green-600">
                            Final Price: ₱{{ number_format($selectedProduct->getDiscountedPrice(), 2) }}
                        </p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $selectedProduct->description ?? 'No description available.'
                            }}
                        </p>
                    </div>
                </div>

                <!-- Analytics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
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

                <!-- DISCOUNT SECTION -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-800">🏷️ Discount</h4>
                        @if(!$editMode)
                        <button wire:click="enableEditMode"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                            ✏️ Edit Discount
                        </button>
                        @endif
                    </div>

                    @if($editMode)
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3 border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Discount Type</label>
                                <select wire:model="discount_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                                    <option value="none">No Discount</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (₱)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Discount Value</label>
                                <input type="number" step="0.01" min="0" wire:model="discount_value"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="datetime-local" wire:model="discount_start"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                                <input type="datetime-local" wire:model="discount_end"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button wire:click="saveDiscount"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                💾 Save Discount
                            </button>
                            <button wire:click="$set('editMode', false)"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @else
                    @if($selectedProduct->isDiscounted())
                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                        <p class="text-sm text-green-800">
                            ✅ Discount active: <span class="font-bold">{{ $selectedProduct->getDiscountLabel() }}</span>
                        </p>
                        <p class="text-sm text-green-700 mt-1">
                            Original Price: ₱{{ number_format($selectedProduct->price, 2) }}
                            → Final Price: <span class="font-bold">₱{{
                                number_format($selectedProduct->getDiscountedPrice(), 2) }}</span>
                        </p>
                        @if($selectedProduct->discount_start || $selectedProduct->discount_end)
                        <p class="text-xs text-green-600 mt-1">
                            @if($selectedProduct->discount_start)
                            Starts: {{ \Carbon\Carbon::parse($selectedProduct->discount_start)->format('M d, Y h:i A')
                            }}
                            @endif
                            @if($selectedProduct->discount_start && $selectedProduct->discount_end) • @endif
                            @if($selectedProduct->discount_end)
                            Ends: {{ \Carbon\Carbon::parse($selectedProduct->discount_end)->format('M d, Y h:i A') }}
                            @endif
                        </p>
                        @endif
                    </div>
                    @else
                    <p class="text-sm text-gray-500">No discount set for this product.</p>
                    @endif
                    @endif
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
                        <p class="text-sm text-gray-500">No branches assigned.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <button wire:click="closeProductModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Unlock scroll when modal closes -->
    @push('scripts')
    <script>
        document.addEventListener('livewire:init', function() {
            Livewire.on('product-modal-closed', function() {
                document.body.classList.remove('overflow-hidden');
            });
        });
    </script>
    @endpush
</div>