<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('livewire.customer.cart') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">Checkout</h1>
        </div>

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
        @endif

        @if($stockWarning)
        <div
            class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-start justify-between gap-3">
            <span>{{ $stockWarning }}</span>
            <button wire:click="dismissStockWarning" class="text-red-700 hover:text-red-900 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- LEFT COLUMN -->
            <div class="lg:col-span-8 space-y-4">

                @foreach($shopGroups as $shopId => $shopData)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-amber-50 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 border border-amber-200 bg-white">
                                @if($shopData['shop']->shop_image)
                                <img src="{{ asset($shopData['shop']->shop_image) }}"
                                    alt="{{ $shopData['shop']->shop_name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-amber-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $shopData['shop']->shop_name }}</p>
                                <p class="text-sm text-gray-500">{{ $shopData['shop']->address ?? 'Victorias City' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($shopData['items'] as $item)
                        @php
                        $product = $item->product;
                        $isDiscounted = $product && $product->isDiscounted();
                        $displayPrice = $isDiscounted ? $product->getDiscountedPrice() : $product->price;
                        $originalPrice = $product->price ?? 0;
                        $discountLabel = $isDiscounted ? $product->getDiscountLabel() : null;
                        @endphp
                        <div class="p-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-16 h-16 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    @if($product && $product->image_url)
                                    <img src="{{ asset($product->image_url) }}" class="w-full h-full object-cover">
                                    @else
                                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.545 1.545 0 013 15.546V18a1 1 0 001 1h16a1 1 0 001-1v-2.454z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.5 10.5v3M8 8v3M12 6v3M16 8v3M19.5 10.5v3"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-800">{{ $product->name ?? 'Product Unavailable' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-1 text-sm">
                                        <span class="text-gray-600">Qty: {{ $item->quantity }}</span>
                                        <span class="text-gray-300">|</span>
                                        @if($isDiscounted)
                                        <span class="text-green-600 font-medium">₱{{ number_format($displayPrice, 2)
                                            }}</span>
                                        <span class="text-gray-400 line-through text-xs">₱{{
                                            number_format($originalPrice, 2) }}</span>
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">{{
                                            $discountLabel }}</span>
                                        @else
                                        <span class="text-amber-600 font-medium">₱{{ number_format($displayPrice, 2)
                                            }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-semibold text-gray-800">₱{{ number_format($displayPrice *
                                        $item->quantity, 2) }}</p>
                                </div>
                            </div>

                            <!-- Branch & Time Selection -->
                            <div class="mt-3 pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Pickup
                                        Branch</p>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                        $availableBranches = $this->getAvailableBranches($item->id);
                                        @endphp
                                        @foreach($availableBranches as $branch)
                                        <label class="flex items-center gap-2 px-3 py-1.5 border rounded-lg cursor-pointer text-sm transition
                                            {{ isset($branchSelections[$item->id]) && $branchSelections[$item->id] == $branch->id
                                                ? 'border-amber-500 bg-amber-50 text-amber-700'
                                                : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50/50' }}">
                                            <input type="radio" wire:model.live="branchSelections.{{ $item->id }}"
                                                value="{{ $branch->id }}" class="text-amber-600 focus:ring-amber-500">
                                            <span>{{ $branch->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error("branchSelections.{$item->id}")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Pickup
                                        Time</p>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Set Pickup Time</label>
                                    <input type="datetime-local" wire:model.live="pickupTimes.{{ $item->id }}"
                                        id="pickup_time_{{ $item->id }}"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm pointer-events-none">
                                    <div class="flex justify-end gap-3 mt-1">
                                        <button type="button"
                                            onclick="document.getElementById('pickup_time_{{ $item->id }}').value = ''; $wire.set('pickupTimes.{{ $item->id }}', '')"
                                            class="text-sm text-gray-500 hover:text-gray-700 transition">
                                            Clear
                                        </button>
                                        <button type="button"
                                            onclick="document.getElementById('pickup_time_{{ $item->id }}').showPicker()"
                                            class="text-sm text-amber-600 hover:text-amber-700 font-medium transition">
                                            Set
                                        </button>
                                    </div>
                                    @error("pickupTimes.{$item->id}")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Per-Item Note -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <label
                                    class="block text-xs font-medium text-gray-600 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Note for this item <span
                                        class="text-gray-400 font-normal normal-case">(Optional)</span>
                                </label>
                                <textarea wire:model.live.debounce.500ms="itemNotes.{{ $item->id }}" rows="2"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                                    placeholder="Any special requests for {{ $product->name ?? 'this item' }}?"></textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

            </div>

            <!-- RIGHT COLUMN -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 sticky top-24">
                    <h3 class="text-base font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-4">Order Summary
                    </h3>

                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Pickup Details</p>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($cartItems as $item)
                            @php
                            $branchId = $branchSelections[$item->id] ?? null;
                            $branchName = $branchId ? \App\Models\Branch::find($branchId)?->name : 'Not selected';
                            $pickupTime = $pickupTimes[$item->id] ?? '';
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-2.5">
                                <p class="text-sm font-medium text-gray-700 truncate">{{ $item->product->name }}</p>
                                <div class="flex justify-between items-center mt-0.5">
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $branchName }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        @if($pickupTime)
                                        {{ \Carbon\Carbon::parse($pickupTime)->format('M d, h:i A') }}
                                        @else
                                        Not set
                                        @endif
                                    </span>
                                </div>
                                @if(!empty($itemNotes[$item->id]))
                                <p class="text-xs text-amber-600 mt-1 italic truncate inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    {{ $itemNotes[$item->id] }}
                                </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Payment Method</p>
                        <div class="space-y-1.5">
                            <label
                                class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer transition text-sm
                                {{ $payment_method === 'paymongo' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="paymongo"
                                    class="text-amber-600 focus:ring-amber-500">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                PayMongo
                            </label>

                            <label
                                class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer transition text-sm
                                {{ $payment_method === 'pickup_payment' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="pickup_payment"
                                    class="text-amber-600 focus:ring-amber-500">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Cash on Pickup
                            </label>
                            @error('payment_method')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="space-y-1.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">VAT (12%)</span>
                            <span class="text-gray-700">₱{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Service Fee</span>
                            <span class="text-gray-700">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-800">Total</span>
                            <span class="text-amber-600">₱{{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>

                    <button wire:click="placeOrder" wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="w-full inline-flex items-center justify-center gap-2 mt-4 px-4 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        <span wire:loading.remove class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Place Order
                        </span>
                        <span wire:loading>Processing...</span>
                    </button>

                    <a href="{{ route('livewire.customer.cart') }}"
                        class="mt-2 inline-flex items-center justify-center gap-1 w-full text-center text-xs text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>