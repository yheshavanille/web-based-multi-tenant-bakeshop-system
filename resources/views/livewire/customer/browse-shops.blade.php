<div>
    <div class="max-w-[85rem] px-4 py-6 sm:px-6 sm:py-10 lg:px-8 lg:py-14 mx-auto">

        <!-- HEADER with Back Button -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
            <div class="text-center flex-1 basis-full sm:basis-0">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-slate-100">
                    Browse Bakeshops
                </h2>
                <p class="text-sm text-gray-600 dark:text-slate-300 mt-1">
                    Browse and explore bakeshop listings.
                </p>
            </div>
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
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
                    <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Search bakeshops by name, address, or description..."
                    class="w-full pl-10 pr-10 h-10 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                Showing results for: <span class="font-medium text-amber-600 dark:text-amber-400">{{ $search }}</span>
                <span class="text-gray-400 dark:text-slate-500">({{ $shops->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($shops as $shop)
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden hover:shadow-md transition flex flex-col">
                <div
                    class="h-48 bg-amber-50 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                        class="w-full h-full object-cover">
                    @else
                    <svg class="w-20 h-20 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    @endif
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-slate-100 text-lg">{{ $shop->shop_name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 inline-flex items-center gap-1 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $shop->address ?? 'Victorias City' }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-slate-300 mt-1 line-clamp-2 flex-1">{{ $shop->description ?? 'No description
                        available' }}</p>
                    <a href="{{ route('livewire.customer.view-products', $shop->id) }}"
                        class="mt-3 inline-flex items-center gap-1 text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-medium">
                        Visit Shop
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-gray-500 dark:text-slate-400">
                @if(!empty($search))
                <svg class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-lg">No bakeshops found matching "<span class="font-medium text-amber-600 dark:text-amber-400">{{ $search
                        }}</span>"</p>
                <p class="text-sm text-gray-400 dark:text-slate-500">Try adjusting your search.</p>
                @else
                <svg class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <p class="text-lg">No bakeshops available yet.</p>
                @endif
            </div>
            @endforelse
        </div>

    </div>
</div>