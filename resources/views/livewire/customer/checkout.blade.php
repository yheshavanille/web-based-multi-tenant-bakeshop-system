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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- LEFT COLUMN -->
            <div class="lg:col-span-8 space-y-4">

                <!-- Shop Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-lg">
                            🏪
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $shop->shop_name }}</p>
                            <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-700">Order Items</p>
                    </div>

                    @foreach($cartItems as $item)
                    <div class="p-4 border-b border-gray-100 last:border-0">
                        <!-- Product Row -->
                        <div class="flex items-start gap-4">
                            <div
                                class="w-16 h-16 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($item->product->image_url)
                                <img src="{{ asset($item->product->image_url) }}" class="w-full h-full object-cover">
                                @else
                                <span class="text-2xl">🍰</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Uncategorized' }}
                                </p>
                                <div class="flex items-center gap-3 mt-1 text-sm">
                                    <span class="text-gray-600">Qty: {{ $item->quantity }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-amber-600 font-medium">₱{{ number_format($item->product->price, 2)
                                        }}</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-semibold text-gray-800">₱{{ number_format($item->product->price *
                                    $item->quantity, 2) }}</p>
                            </div>
                        </div>

                        <!-- Branch & Time Selection -->
                        <div class="mt-3 pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Pickup Branch
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                    $availableBranches = $this->getAvailableBranches($item->id);
                                    @endphp
                                    @foreach($availableBranches as $branch)
                                    <label
                                        class="flex items-center gap-2 px-3 py-1.5 border rounded-lg cursor-pointer text-sm transition
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
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Pickup Time
                                </p>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Set Pickup Time</label>
                                <input type="datetime-local" wire:model.live="pickupTimes.{{ $item->id }}"
                                    id="pickup_time_{{ $item->id }}"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                                <div class="flex justify-end gap-3 mt-1">
                                    <button type="button"
                                        onclick="document.getElementById('pickup_time_{{ $item->id }}').value = ''"
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
                    </div>
                    @endforeach
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        📝 Order Notes <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <textarea wire:model="notes" rows="2"
                        class="mt-2 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                        placeholder="Any special requests for the bakeshop?"></textarea>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 sticky top-24">
                    <h3 class="text-base font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-4">Order Summary
                    </h3>

                    <!-- Pickup Details -->
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
                                    <span class="text-xs text-gray-500">📍 {{ $branchName }}</span>
                                    <span class="text-xs text-gray-400">
                                        @if($pickupTime)
                                        {{ \Carbon\Carbon::parse($pickupTime)->format('M d, h:i A') }}
                                        @else
                                        Not set
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Payment -->
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Payment Method</p>
                        <div class="space-y-1.5">
                            <label
                                class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer transition text-sm
                                {{ $payment_method === 'gcash' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300' }}">
                                <input type="radio" wire:model="payment_method" value="gcash"
                                    class="text-amber-600 focus:ring-amber-500">
                                📱 GCash
                            </label>
                            <label
                                class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer transition text-sm
                                {{ $payment_method === 'paymaya' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300' }}">
                                <input type="radio" wire:model="payment_method" value="paymaya"
                                    class="text-amber-600 focus:ring-amber-500">
                                📱 PayMaya
                            </label>
                            <label
                                class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer transition text-sm
                                {{ $payment_method === 'pickup_payment' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300' }}">
                                <input type="radio" wire:model="payment_method" value="pickup_payment"
                                    class="text-amber-600 focus:ring-amber-500">
                                💵 Cash on Pickup
                            </label>
                            @error('payment_method')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Totals -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Service Fee</span>
                            <span class="text-gray-700">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-800">Total</span>
                            <span class="text-amber-600">₱{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button wire:click="placeOrder"
                        class="w-full mt-4 px-4 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        Place Order 🛒
                    </button>

                    <a href="{{ route('livewire.customer.cart') }}"
                        class="block text-center mt-2 text-xs text-gray-400 hover:text-gray-600 transition">
                        ← Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>