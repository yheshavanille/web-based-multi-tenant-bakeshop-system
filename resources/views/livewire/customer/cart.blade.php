<div>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 inline-flex items-center gap-2">
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            My Cart
        </h1>

        @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        {{-- ✅ Persistent stock warning --}}
        @if($stockWarning)
        <div
            class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-start justify-between gap-3">
            <span>{{ $stockWarning }}</span>
            <button wire:click="dismissStockWarning" class="text-red-700 hover:text-red-900 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        @if($cartItems->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex items-center gap-4 p-4 bg-gray-50 border-b border-gray-200">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="selectAll"
                        class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                    <span class="text-sm font-medium text-gray-700">Select All</span>
                </label>
                <span class="text-sm text-gray-500 ml-auto">
                    {{ $this->selectedCount }} of {{ $cartItems->count() }} items selected
                </span>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($cartItems as $item)
                @php
                $product = $item->product;
                $isDiscounted = $product && $product->isDiscounted();
                $displayPrice = $isDiscounted ? $product->getDiscountedPrice() : ($product->price ?? 0);
                $originalPrice = $product->price ?? 0;
                $discountLabel = $isDiscounted ? $product->getDiscountLabel() : null;

                $productUrl = $product
                ? route('livewire.customer.view-products', [
                'shopId' => $product->shop_id,
                'branch' => $item->branch_id,
                ]) . '#product-' . $product->id
                : null;
                @endphp
                <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition"
                    wire:key="cart-item-{{ $item->id }}">
                    <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}"
                        class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">

                    {{-- Product image --}}
                    @if($productUrl)
                    <a href="{{ $productUrl }}" wire:navigate
                        class="w-20 h-20 bg-amber-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0 hover:ring-2 hover:ring-amber-400 transition"
                        title="View {{ $product->name }}">
                        @if($product->image_url)
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover">
                        @else
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                        </svg>
                        @endif
                    </a>
                    @else
                    <div
                        class="w-20 h-20 bg-amber-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                        </svg>
                    </div>
                    @endif

                    <div class="flex-1">
                        @if($productUrl)
                        <a href="{{ $productUrl }}" wire:navigate
                            class="font-semibold text-gray-800 hover:text-amber-600 hover:underline transition"
                            title="View {{ $product->name }}">
                            {{ $product->name }}
                        </a>
                        @else
                        <h3 class="font-semibold text-gray-800">Product Unavailable</h3>
                        @endif

                        <div class="flex items-center gap-2 mt-1">
                            @if($isDiscounted)
                            <span class="text-sm font-bold text-green-600">₱{{ number_format($displayPrice, 2) }}</span>
                            <span class="text-sm text-gray-400 line-through">₱{{ number_format($originalPrice, 2)
                                }}</span>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">{{ $discountLabel
                                }}</span>
                            @else
                            <span class="text-sm text-gray-500">₱{{ number_format($displayPrice, 2) }}</span>
                            @endif
                        </div>

                        @if($product && $product->branches->count() > 0)
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($product->branches as $branch)
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
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                            wire:loading.attr="disabled" wire:target="updateQuantity({{ $item->id }}, *)"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                </path>
                            </svg>
                        </button>
                        <span class="w-10 text-center font-medium">{{ $item->quantity }}</span>
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                            wire:loading.attr="disabled" wire:target="updateQuantity({{ $item->id }}, *)"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="text-right min-w-[80px]">
                        <p class="font-semibold text-amber-600">
                            ₱{{ number_format($displayPrice * $item->quantity, 2) }}
                        </p>
                        <button wire:click="removeFromCart({{ $item->id }})"
                            class="text-xs text-red-500 hover:text-red-700 transition inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-gray-50 p-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">
                            {{ $this->selectedCount }} item(s) selected
                        </p>
                        <p class="text-2xl font-bold text-gray-800">
                            ₱{{ number_format($this->selectedTotal, 2) }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="clearCart"
                            class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                            Clear Cart
                        </button>
                        <button wire:click="checkoutSelected"
                            class="inline-flex items-center gap-2 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                            Checkout Selected
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700">Your cart is empty</h3>
            <p class="text-gray-500 mt-2">Browse our bakeshops and add some delicious items!</p>
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="inline-flex items-center gap-2 mt-4 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                Browse Shops
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>