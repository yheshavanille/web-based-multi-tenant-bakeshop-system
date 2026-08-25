<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        <!-- HEADER with Back Button -->
        <div class="flex items-center justify-between mb-8">
            <div class="text-center flex-1">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Browse Bakeshops
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Browse and explore bakeshop listings.
                </p>
            </div>
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search bakeshops by name, address, or description..."
                    class="w-full pl-10 pr-10 h-10 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500">
                Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
                <span class="text-gray-400">({{ $shops->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($shops as $shop)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div
                    class="h-48 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-6xl">🍰</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $shop->shop_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $shop->description ?? 'No description
                        available' }}</p>
                    <a href="{{ route('livewire.customer.view-products', $shop->id) }}"
                        class="mt-3 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Visit Shop →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                @if(!empty($search))
                <p class="text-lg">No bakeshops found matching "<span class="font-medium text-amber-600">{{ $search
                        }}</span>"</p>
                <p class="text-sm text-gray-400">Try adjusting your search.</p>
                @else
                <p class="text-lg">No bakeshops available yet.</p>
                @endif
            </div>
            @endforelse
        </div>

    </div>
</div>