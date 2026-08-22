<div>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">🛒 My Cart</h1>

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
                <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition"
                    wire:key="cart-item-{{ $item->id }}">
                    <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}"
                        class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">

                    <div
                        class="w-20 h-20 bg-amber-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->image_url)
                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}"
                            class="w-full h-full object-cover">
                        @else
                        <span class="text-3xl">🍰</span>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $item->product?->name ?? 'Product Unavailable' }}
                        </h3>
                        <p class="text-sm text-gray-500">₱{{ number_format($item->product?->price ?? 0, 2) }}</p>
                        @if($item->product && $item->product->branches->count() > 0)
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($item->product->branches as $branch)
                            <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                                📍 {{ $branch->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                            wire:loading.attr="disabled" wire:target="updateQuantity({{ $item->id }}, *)"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-lg font-medium">−</span>
                        </button>
                        <span class="w-10 text-center font-medium">{{ $item->quantity }}</span>
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                            wire:loading.attr="disabled" wire:target="updateQuantity({{ $item->id }}, *)"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-lg font-medium">+</span>
                        </button>
                    </div>

                    <div class="text-right min-w-[80px]">
                        <p class="font-semibold text-amber-600">
                            ₱{{ number_format(($item->product?->price ?? 0) * $item->quantity, 2) }}
                        </p>
                        <button wire:click="removeFromCart({{ $item->id }})"
                            class="text-xs text-red-500 hover:text-red-700 transition">
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
                            class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                            Checkout Selected →
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-6xl mb-4">🛒</p>
            <h3 class="text-xl font-semibold text-gray-700">Your cart is empty</h3>
            <p class="text-gray-500 mt-2">Browse our bakeshops and add some delicious items!</p>
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="inline-block mt-4 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                Browse Shops →
            </a>
        </div>
        @endif
    </div>
</div>