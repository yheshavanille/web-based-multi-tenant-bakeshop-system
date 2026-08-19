<div>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📦 Manage Products</h1>
                <p class="text-sm text-gray-500">
                    Manage products for <span class="font-medium text-blue-600">{{ $branch->name }}</span>
                </p>
            </div>
            <div class="flex gap-3">
                <button wire:click="createNew"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                    + Add Product
                </button>
                <button wire:click="toggleDeleted"
                    class="px-4 py-2 {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                </button>
            </div>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Product Form -->
        @if($showForm)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $editing ? '✏️ Edit Product' : '➕ Add New Product' }}
            </h2>

            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                <!-- ✅ Stock field removed for Order Manager -->

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

                <!-- Image Preview -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image Preview</label>
                    <div class="flex justify-center">
                        <div
                            class="w-48 h-48 bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm">
                            @if($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif($image_url)
                            <img src="{{ $image_url }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">No image selected</p>
                                <p class="text-xs text-gray-400">Upload an image above</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 flex gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        {{ $editing ? 'Update Product' : 'Save Product' }}
                    </button>
                    <button type="button" wire:click="cancel"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Product List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Price</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Stock</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Category</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($products as $product)
                        @if($product->trashed())
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">🗑️</span>
                                    <p class="text-sm font-medium text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">This product is deleted.</p>
                                    <button wire:click="restore({{ $product->id }})"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                        Restore Product
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @else
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->image_url)
                                    <img src="{{ asset($product->image_url) }}" class="w-8 h-8 rounded-lg object-cover">
                                    @endif
                                    <span class="font-medium text-gray-800">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">₱{{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3">
                                @php
                                $stock = $product->branches->firstWhere('id', $branch->id)?->pivot->stock ?? 0;
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                    {{ $stock > 10 ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $stock <= 10 && $stock > 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $stock <= 0 ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <button wire:click="edit({{ $product->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $product->id }})"
                                    onclick="confirm('Delete this product?') || event.stopImmediatePropagation()"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">📭</span>
                                    <p>{{ $showDeleted ? 'No deleted products found.' : 'No products for this branch
                                        yet.' }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>