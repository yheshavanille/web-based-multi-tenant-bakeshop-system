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

        <!-- PRODUCTS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

            @forelse($products as $product)

            @php
            // Calculate analytics for each product
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

                    <p class="text-lg font-bold text-amber-600 mt-1">₱{{ number_format($product->price, 2) }}</p>

                    <!-- ✅ STOCK REMOVED -->

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
                        <p class="text-sm text-gray-600">{{ $selectedProduct->description ?? 'No description available.'
                            }}</p>
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