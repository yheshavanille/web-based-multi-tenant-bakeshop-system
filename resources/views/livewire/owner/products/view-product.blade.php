<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        <div class="mt-12 max-w-full mx-auto">

            <!-- FLASH MESSAGE -->
            @if (session()->has('message'))
            <div
                class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
                <span>{{ session('message') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
            </div>
            @endif

            <!-- HEADER ROW (TOP RIGHT BUTTONS) -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-2">
                    <button wire:click="toggleDeleted"
                        class="px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                        {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                    </button>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('livewire.owner.products.create-product') }}"
                        class="inline-flex items-center px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                        + Add Product
                    </a>
                    <a href="{{ route('livewire.owner.dashboard') }}"
                        class="inline-flex items-center px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        ← Dashboard
                    </a>
                </div>
            </div>

            <!-- HEADER -->
            <div class="flex flex-col items-center justify-center mb-6 text-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    View Products
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Manage product listings and details.
                </p>
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
                <p class="text-sm text-red-600 mt-1">
                    Showing deleted products
                </p>
                @endif
            </div>

            <!-- PRODUCTS GRID -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($products as $product)

                <div
                    class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden hover:shadow-md transition {{ $product->trashed() ? 'opacity-75 border-red-200' : '' }}">

                    <!-- IMAGE -->
                    <div class="p-3">
                        <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                            @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- CONTENT -->
                    <div class="p-4 md:p-6 flex-1">
                        <div class="flex items-start justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">
                                {{ $product->name }}
                            </h3>
                            @if($product->trashed())
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Deleted</span>
                            @endif
                        </div>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ $product->category->name ?? 'No Category' }}
                        </p>

                        <p class="font-medium mt-1 text-amber-600">
                            ₱{{ number_format($product->price, 2) }}
                        </p>

                        <!-- BRANCHES -->
                        <div class="mt-2">
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

                        <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                            {{ $product->description }}
                        </p>

                    </div>

                    <!-- ACTIONS -->
                    <div class="mt-auto flex border-t border-gray-200 divide-x divide-gray-200">

                        @if($product->trashed())
                        <!-- Restore -->
                        <button wire:click="restore({{ $product->id }})"
                            class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium text-green-600 hover:bg-green-50">
                            🔄 Restore
                        </button>
                        <!-- Permanent Delete -->
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Permanently delete this product?') || event.stopImmediatePropagation()"
                            class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium text-red-600 hover:bg-red-50">
                            🗑️ Delete Permanently
                        </button>
                        @else
                        <!-- EDIT -->
                        <a href="{{ route('livewire.owner.products.edit-product', ['productId' => $product->id]) }}"
                            class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium hover:bg-gray-50">
                            ✏️ Edit
                        </a>
                        <!-- DELETE -->
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Delete this product?') || event.stopImmediatePropagation()"
                            class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium text-red-600 hover:bg-red-50">
                            🗑️ Delete
                        </button>
                        @endif

                    </div>

                </div>

                @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <p class="text-lg">No products found</p>
                </div>
                @endforelse

            </div>

        </div>

    </div>
</div>