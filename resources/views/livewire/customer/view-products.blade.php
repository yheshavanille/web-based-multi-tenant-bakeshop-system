<div>
    <div class="max-w-[85rem] px-4 py-10 mx-auto space-y-10">

        <!-- HEADER -->
        <div class="flex flex-col items-center justify-center mb-6 text-center">
            <h2 class="text-xl font-semibold text-gray-800">
                View Shop Details
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                View shop details, products, and information.
            </p>
        </div>

        <!-- SHOP CARD -->
        <div class="group bg-white border border-gray-200 ring-1 ring-gray-100 shadow-sm rounded-xl overflow-hidden">

            <!-- IMAGE -->
            <div class="p-3">
                <div class="h-64 bg-gray-100 rounded-lg overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                    @endif
                </div>
            </div>

            <!-- CONTENT -->
            <div class="p-6 space-y-2">
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $shop->shop_name }}
                </h1>
                <p class="text-sm text-gray-500">
                    Address: {{ $shop->address ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-600">
                    {{ $shop->description ?? 'No description available' }}
                </p>
                <p class="text-sm text-gray-500">
                    Owner: {{ $shop->user->name ?? 'N/A' }}
                </p>
            </div>
        </div>

        <!-- PRODUCTS SECTION -->
        <div class="bg-white border border-gray-200 ring-1 ring-gray-100 shadow-sm rounded-xl p-6 space-y-6">

            <!-- TITLE -->
            <div class="text-center">
                <h2 class="text-xl font-semibold text-gray-800">Products</h2>
            </div>

            <!-- FILTER -->
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($products as $product)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                    @endif
                    <h3 class="font-semibold text-gray-800">
                        {{ $product->name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $product->category->name ?? 'No Category' }}
                    </p>
                    <p class="font-medium text-amber-600">
                        ₱{{ number_format($product->price, 2) }}
                    </p>
                    <p class="text-sm text-gray-600 line-clamp-2">
                        {{ $product->description }}
                    </p>

                    <!-- Add to Cart Button -->
                    <button wire:click="addToCart({{ $product->id }})"
                        class="mt-3 w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        Add to Cart 🛒
                    </button>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    No products found for this shop.
                </div>
                @endforelse
            </div>

        </div>

    </div>
</div>