<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        <!-- FLASH MESSAGE -->
        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Products</h1>
                <p class="text-sm text-gray-500">View product listings and details</p>
                @if($selectedBranchId)
                @php
                $branch = auth()->user()->shop->branches->firstWhere('id', $selectedBranchId);
                @endphp
                @if($branch)
                <p class="text-sm text-amber-600 mt-1 inline-flex items-center gap-1.5">
                    Showing products for:
                    <span class="inline-flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $branch->name }}
                    </span>
                </p>
                @endif
                @endif
                @if($showDeleted)
                <p class="text-sm text-red-600 mt-1">Showing deleted products</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @if(!$showDeleted)
                <button wire:click="showCreateForm"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Product
                </button>
                @endif
                <button wire:click="toggleDeleted"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                    @if($showDeleted)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Show Active
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Show Deleted
                    @endif
                </button>
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        <!-- SEARCH BAR + BRANCH DROPDOWN -->
        <div class="mb-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                    @endif
                </div>

                <div class="sm:w-64">
                    <select wire:model.live="selectedBranchId"
                        style="appearance: auto; -webkit-appearance: auto; -moz-appearance: auto; background-image: none;"
                        class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
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
        <div id="product-form" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 scroll-mt-20">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 inline-flex items-center gap-2">
                @if($editing)
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit Product
                @else
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Product
                @endif
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
                        style="appearance: auto; -webkit-appearance: auto; -moz-appearance: auto; background-image: none;"
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

                <!-- BRANCH CHECKBOXES WITH DELTA INPUT -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available Branches</label>
                    <p class="text-xs text-gray-500 mb-3">
                        @if($editing)
                        Enter the amount to add (positive) or remove (negative). Current stock is shown on the right.
                        @else
                        Select which branches this product is available at, and set the initial stock for each.
                        @endif
                    </p>

                    @if($branches->count() > 0)
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($branches as $branch)
                        @php
                        $isChecked = in_array($branch->id, $selectedBranches);
                        $currentStock = $editing
                        ? (int) ($originalValues['branch_stocks'][$branch->id] ?? 0)
                        : 0;
                        $deltaRaw = $this->branch_stocks[$branch->id] ?? '';
                        $delta = ($deltaRaw === '' || $deltaRaw === null) ? 0 : (int) $deltaRaw;
                        $preview = max(0, $currentStock + $delta);
                        @endphp
                        <div class="border border-gray-200 rounded-lg overflow-hidden transition
                                {{ $isChecked ? 'bg-amber-50 border-amber-300' : 'hover:bg-gray-50' }}">

                            <label class="flex items-start gap-3 p-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedBranches" value="{{ $branch->id }}"
                                    class="mt-0.5 w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $branch->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $branch->address }}</p>
                                    <p class="text-xs text-gray-400">
                                        Status: {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                    </p>
                                </div>
                            </label>

                            @if($isChecked)
                            <div class="px-3 pb-3 pt-1 border-t border-amber-200/50 space-y-3">

                                {{-- Current stock — always visible --}}
                                @if($editing)
                                <div class="bg-white border border-amber-200 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-0.5">Current Stock</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $currentStock }}</p>
                                </div>
                                @endif

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            @if($editing)
                                            Add / Remove
                                            @else
                                            Initial Stock
                                            @endif
                                        </label>
                                        <input type="number" wire:model.live="branch_stocks.{{ $branch->id }}"
                                            class="py-2 px-3 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white"
                                            placeholder="{{ $editing ? '+2 or -3' : '0' }}">
                                    </div>

                                    @if($editing)
                                    <div class="flex flex-col justify-end">
                                        <p class="text-xs text-gray-500 mb-1">After Change</p>
                                        <p class="text-sm font-medium text-gray-800">
                                            @if($delta !== 0)
                                            <span class="text-gray-500">{{ $currentStock }}</span>
                                            <span class="{{ $delta > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $delta > 0 ? '+' : '' }}{{ $delta }}
                                            </span>
                                            <span class="text-gray-400">=</span>
                                            <span class="text-2xl font-bold text-amber-600">{{ $preview }}</span>
                                            @else
                                            <span class="text-gray-400 italic">Enter a value above</span>
                                            @endif
                                        </p>
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Notes <span class="text-gray-400 font-normal">(optional)</span>
                                    </label>
                                    <input type="text" wire:model="branch_notes.{{ $branch->id }}"
                                        placeholder="e.g. freshly made"
                                        class="w-full py-2 px-3 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @error('selectedBranches')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @else
                    <div
                        class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800 inline-flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p>No branches found. Please <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                                class="text-amber-600 hover:underline">create a branch</a> first.</p>
                    </div>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DISCOUNT SECTION -->
                <div class="border-t border-gray-200 pt-4 mt-4 md:col-span-2">
                    <h3 class="text-md font-semibold text-gray-800 mb-3 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        Discount Settings
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                            <select wire:model.live="discount_type"
                                style="appearance: auto; -webkit-appearance: auto; -moz-appearance: auto; background-image: none;"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="none">No Discount</option>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₱)</option>
                            </select>
                            @error('discount_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value</label>
                            <input type="number" step="0.01" wire:model.live="discount_value"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                min="0" {{ $discount_type==='none' ? 'disabled' : '' }}>
                            @error('discount_value')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($discount_type !== 'none' && $discount_value > 0 && $price > 0)
                    @php
                    $discountedPrice = $price;
                    if ($discount_type === 'percentage') {
                    $discountedPrice = $price * (1 - $discount_value / 100);
                    } elseif ($discount_type === 'fixed') {
                    $discountedPrice = max(0, $price - $discount_value);
                    }
                    $discountedPrice = max(0, $discountedPrice);
                    @endphp
                    <div class="md:col-span-2 mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-4 flex-wrap">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Original Price:</span>
                                <span class="text-gray-500 line-through">₱{{ number_format($price, 2) }}</span>
                            </p>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Discounted Price:</span>
                                <span class="text-lg font-bold text-green-600">₱{{ number_format($discountedPrice, 2)
                                    }}</span>
                            </p>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                {{ $discount_type === 'percentage' ? $discount_value . '% OFF' : '₱' .
                                number_format($discount_value, 2) . ' OFF' }}
                            </span>
                            <span class="text-xs text-gray-400">
                                (Save ₱{{ number_format($price - $discountedPrice, 2) }})
                            </span>
                        </div>
                    </div>
                    @endif
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
                            <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
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
            $branchIds = $product->branches->pluck('id')->toArray();

            if (!empty($branchIds)) {
            $orderItems = \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($q) use ($branchIds) {
            $q->where('status', 'completed')
            ->whereIn('branch_id', $branchIds);
            })
            ->get();
            } else {
            $orderItems = \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($q) {
            $q->where('status', 'completed');
            })
            ->get();
            }

            $totalSold = $orderItems->sum('quantity');
            $totalRevenue = $orderItems->sum(function($item) {
            return $item->quantity * $item->price;
            });
            $stock = $product->current_stock ?? 0;
            @endphp

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col h-full {{ $product->trashed() ? 'opacity-75 border-red-200' : '' }}">

                <div class="h-48 bg-gray-100 overflow-hidden flex-shrink-0">
                    @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" class="w-full h-full object-cover">
                    @else
                    <div
                        class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                        <svg class="w-14 h-14 mb-2 text-amber-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                        </svg>
                        <span class="text-sm">No Image</span>
                    </div>
                    @endif
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-gray-800 text-lg leading-tight">{{ $product->name }}</h3>
                        @if($product->trashed())
                        <span
                            class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full flex-shrink-0">Deleted</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-500 mt-0.5">{{ $product->category->name ?? 'No Category' }}</p>

                    <p class="text-lg font-bold mt-1">
                        @if($product->isDiscounted())
                        <span class="text-red-600">₱{{ number_format($product->getDiscountedPrice(), 2) }}</span>
                        <span class="text-sm text-gray-400 line-through ml-2">₱{{ number_format($product->price, 2)
                            }}</span>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-1">{{
                            $product->getDiscountLabel() }}</span>
                        @else
                        <span class="text-amber-600">₱{{ number_format($product->price, 2) }}</span>
                        @endif
                    </p>

                    <div class="mt-1 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full
                            {{ $stock > 10 ? 'bg-green-100 text-green-800' : '' }}
                            {{ $stock <= 10 && $stock > 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $stock <= 0 ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $stock }} in stock
                        </span>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">
                            {{ $product->product_reviews_count ?? 0 }} reviews
                        </span>
                    </div>

                    <div class="mt-2 flex items-center gap-3 flex-wrap">
                        <span class="text-xs text-gray-500">Sold:</span>
                        <span class="text-xs font-semibold text-green-600">{{ $totalSold }}</span>
                        <span class="text-xs text-gray-300">|</span>
                        <span class="text-xs font-semibold text-amber-600">₱{{ number_format($totalRevenue, 2) }}</span>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs font-medium text-gray-700">Available at:</p>
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
                            <span class="text-xs text-gray-400">No branches assigned</span>
                            @endforelse
                        </div>
                    </div>

                    @if($product->description)
                    <p class="text-sm text-gray-600 mt-3 line-clamp-2 flex-1">{{ $product->description }}</p>
                    @endif
                </div>

                <div class="border-t border-gray-100 flex-shrink-0">
                    @if($product->trashed())
                    <div class="flex divide-x divide-gray-200">
                        <button wire:click="restore({{ $product->id }})"
                            class="flex-1 py-3 text-sm font-medium text-green-600 hover:bg-green-50 transition">
                            Restore
                        </button>
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Permanently delete this product?') || event.stopImmediatePropagation()"
                            class="flex-1 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            Delete
                        </button>
                    </div>
                    @else
                    <div class="flex divide-x divide-gray-200">
                        <button wire:click="viewProductDetails({{ $product->id }})"
                            class="flex-1 py-3 text-sm font-medium text-blue-600 hover:bg-blue-50 transition">
                            View Details
                        </button>
                        <button wire:click="editProduct({{ $product->id }})"
                            class="flex-1 py-3 text-sm font-medium text-amber-600 hover:bg-amber-50 transition">
                            Edit
                        </button>
                        <button wire:click="delete({{ $product->id }})"
                            onclick="confirm('Delete this product?') || event.stopImmediatePropagation()"
                            class="flex-1 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            Delete
                        </button>
                    </div>
                    @endif
                </div>

            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
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
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeProductModal"></div>

        <div class="relative z-10 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            style="max-height: 90vh;">

            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Product Details</h3>
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

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                        @if($selectedProduct->image_url)
                        <img src="{{ asset($selectedProduct->image_url) }}" class="w-full h-full object-cover">
                        @else
                        <div
                            class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                            <svg class="w-14 h-14 mb-2 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                            </svg>
                            <span class="text-sm">No Image</span>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold text-gray-800">{{ $selectedProduct->name }}</h2>
                        <p class="text-sm text-gray-500"> {{ $selectedProduct->category->name ?? 'Uncategorized' }}
                        </p>
                        <p class="text-2xl font-bold">
                            @if($selectedProduct->isDiscounted())
                            <span class="text-red-600">₱{{ number_format($selectedProduct->getDiscountedPrice(), 2)
                                }}</span>
                            <span class="text-sm text-gray-400 line-through ml-2">₱{{
                                number_format($selectedProduct->price, 2) }}</span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-1">{{
                                $selectedProduct->getDiscountLabel() }}</span>
                            @else
                            <span class="text-amber-600">₱{{ number_format($selectedProduct->price, 2) }}</span>
                            @endif
                        </p>
                        @php
                        $totalStock = $selectedProduct->branches->sum('pivot.stock');
                        @endphp
                        <p class="text-sm text-gray-600">Stock: <span class="font-medium">{{ $totalStock }}</span>
                            units available</p>
                        @if($selectedProduct->description)
                        <p class="text-sm text-gray-600 mt-2 border-t border-gray-100 pt-2">{{
                            $selectedProduct->description }}</p>
                        @endif
                    </div>
                </div>

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

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Stock by Branch
                    </h4>
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

                <div class="border-t border-gray-200 pt-4">
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
                                        @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>
                                            @else
                                            <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>
                                            @endif
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

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeProductModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
    {{-- Auto-scroll to form on create/edit --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-product-form', () => {
                setTimeout(() => {
                    const el = document.getElementById('product-form');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 50);
            });
        });
    </script>
</div>