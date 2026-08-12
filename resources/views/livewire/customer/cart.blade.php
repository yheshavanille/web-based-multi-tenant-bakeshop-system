<div>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">🛒 My Cart</h1>

        @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if($cartItems->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Cart Items -->
            <div class="divide-y divide-gray-200">
                @foreach($cartItems as $item)
                <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition">
                    <!-- Product Image -->
                    <div
                        class="w-20 h-20 bg-amber-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($item->product->image_url)
                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}"
                            class="w-full h-full object-cover">
                        @else
                        <span class="text-3xl">🍰</span>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-500">₱{{ number_format($item->product->price, 2) }}</p>
                    </div>

                    <!-- Quantity -->
                    <div class="flex items-center gap-2">
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            <span class="text-lg font-medium">−</span>
                        </button>
                        <span class="w-10 text-center font-medium">{{ $item->quantity }}</span>
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            <span class="text-lg font-medium">+</span>
                        </button>
                    </div>

                    <!-- Subtotal & Remove -->
                    <div class="text-right min-w-[80px]">
                        <p class="font-semibold text-amber-600">₱{{ number_format($item->product->price *
                            $item->quantity, 2) }}</p>
                        <button wire:click="removeFromCart({{ $item->id }})"
                            class="text-xs text-red-500 hover:text-red-700 transition">
                            Remove
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Cart Total -->
            <div class="bg-gray-50 p-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-2xl font-bold text-gray-800">₱{{ number_format($total, 2) }}</p>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="clearCart"
                            class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                            Clear Cart
                        </button>
                        <a href="#" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                            Proceed to Checkout →
                        </a>
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