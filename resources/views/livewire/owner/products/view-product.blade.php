<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        <div class="mt-12 max-w-full mx-auto">

            <!-- HEADER ROW (TOP RIGHT BUTTONS) -->
            <div class="flex justify-end mb-6 gap-2">
                <a href="{{ route('livewire.owner.products.create-product') }}"
                    class="inline-flex items-center px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    + Add Product
                </a>

                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    ← Dashboard
                </a>
            </div>

            <!-- HEADER -->
            <div class="flex flex-col items-center justify-center mb-6 text-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    View Products
                </h2>

                <p class="text-sm text-gray-600 dark:text-neutral-400 mt-1">
                    Manage product listings and details.
                </p>
            </div>

            <!-- FILTER -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">
                    Filter by Category
                </label>

                <select wire:model.live="selectedCategory" class="w-full sm:w-64 border border-gray-200 rounded-lg p-2">

                    <option value="all">All</option>

                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- PRODUCTS GRID -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($products as $product)

                <div
                    class="group flex flex-col h-full bg-white border border-gray-200 ring-1 ring-gray-100 shadow-sm rounded-xl overflow-hidden">

                    <!-- IMAGE -->
                    <div class="p-3">
                        <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                            @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- CONTENT -->
                    <div class="p-4 md:p-6">

                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ $product->name }}
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ $product->category->name ?? 'No Category' }}
                        </p>

                        <p class="font-medium mt-1">
                            ₱{{ $product->price }}
                        </p>

                        <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                            {{ $product->description }}
                        </p>

                    </div>

                    <!-- ACTIONS -->
                    <div class="mt-auto flex border-t border-gray-200 divide-x divide-gray-200">

                        <!-- EDIT -->
                        <a href="{{ route('livewire.owner.products.edit-product', ['productId' => $product->id]) }}"
                            class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium hover:bg-gray-50">
                            Edit
                        </a>

                        <!-- DELETE -->
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Delete this product?') || event.stopImmediatePropagation()"
                            class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium text-red-600 hover:bg-red-50">
                            Delete
                        </button>

                    </div>

                </div>

                @empty

                <div class="col-span-full text-center text-gray-500">
                    No products available
                </div>

                @endforelse

            </div>

        </div>

    </div>
</div>