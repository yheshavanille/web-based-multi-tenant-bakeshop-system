<div>
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    @if($showDeleted)
                    Deleted Bakeshops
                    @else
                    View Bakeshops
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $showDeleted ? 'View and restore deleted bakeshops.' : 'View bakeshops, products, and employees.'
                    }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($deletedCount > 0)
                <span class="text-sm text-gray-500">
                    {{ $deletedCount }} deleted
                </span>
                @endif
                <button wire:click="toggleDeleted"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition {{ $showDeleted ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-gray-600 text-white hover:bg-gray-700' }}">
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
                <a href="{{ route('livewire.admin.admin-dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search shops by name, address, or owner..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                    style="position: absolute;">
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

        @if (session()->has('message'))
        <div
            class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-600 text-white flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
            {{ session('message') }}
        </div>
        @endif

        <div class="mb-5 flex items-center justify-between pb-3">
            <div>
                <p class="text-sm font-semibold text-slate-800">
                    {{ $showDeleted ? 'Archived listings' : 'Active listings' }}
                </p>
                <p class="mt-1 text-xs text-slate-500">{{ $shops->count() }} {{ $shops->count() === 1 ? 'bakeshop' :
                    'bakeshops' }} shown</p>
            </div>
            <span class="hidden text-xs font-medium text-slate-400 sm:block">Select a listing to view details</span>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($shops as $shop)
            @php($isDeleted = $shop->trashed())
            <article
                class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:shadow-lg {{ $isDeleted ? 'border-red-200 opacity-75' : '' }}">
                <div class="relative h-40 overflow-hidden bg-gradient-to-r from-amber-50 to-orange-50">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                    <div class="flex h-full flex-col items-center justify-center text-gray-400">
                        <svg class="w-14 h-14 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span class="mt-1 text-xs">No Image</span>
                    </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-slate-950/40 to-transparent">
                    </div>
                    @if($isDeleted)
                    <span
                        class="absolute right-4 top-4 rounded-full bg-rose-600 px-3 py-1 text-xs font-bold text-white shadow-sm">Deleted</span>
                    @else
                    <span
                        class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm">Active</span>
                    @endif
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <div class="mb-4">
                        <h2 class="truncate text-lg font-semibold text-slate-900">{{ $shop->shop_name ?: 'Unnamed
                            bakeshop' }}</h2>
                        <p class="mt-1 text-sm text-slate-500 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $shop->address ?: 'Address not provided' }}
                        </p>
                    </div>

                    <div class="mb-4 rounded-xl bg-slate-50 p-3">
                        <p class="mb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Owner</p>
                        <p class="truncate text-sm font-semibold text-slate-800">{{ $shop->user?->name ?? 'N/A' }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $shop->user?->email ?? 'No email available' }}</p>
                    </div>

                    <p class="mb-5 line-clamp-2 min-h-10 text-sm leading-5 text-slate-500">{{ $shop->description ?: 'No
                        description has been added for this listing.' }}</p>

                    <div class="mt-auto flex flex-col gap-2 border-t border-gray-100 pt-3">
                        @if($isDeleted)
                        <button wire:click="restore({{ $shop->id }})"
                            class="inline-flex items-center justify-center gap-2 flex-1 rounded-lg bg-emerald-50 px-3 py-2.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Restore
                        </button>
                        <button wire:click="forceDelete({{ $shop->id }})"
                            onclick="confirm('Permanently delete this shop? This cannot be undone.') || event.stopImmediatePropagation()"
                            class="inline-flex items-center justify-center gap-2 flex-1 rounded-lg bg-rose-50 px-3 py-2.5 text-xs font-bold text-rose-700 transition hover:bg-rose-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Delete forever
                        </button>
                        @else
                        <div class="flex gap-2">
                            <a href="{{ route('livewire.admin.pages.shops.shop-details', ['shopId' => $shop->id]) }}"
                                class="inline-flex items-center justify-center gap-2 flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                View Shop Details
                            </a>
                            {{-- Opens modal instead of inline confirm --}}
                            <button wire:click="openDeleteModal({{ $shop->id }})"
                                class="inline-flex items-center justify-center rounded-lg bg-rose-50 px-4 py-2.5 text-rose-700 transition hover:bg-rose-100"
                                title="Delete shop">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <div
                class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <p class="mt-5 text-lg font-semibold text-slate-800">
                    @if(!empty($search))
                    No shops found matching "<span class="text-amber-600">{{ $search }}</span>"
                    @elseif($showDeleted)
                    No deleted shops found
                    @else
                    No bakeshops available
                    @endif
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    @if(!empty($search))
                    Try adjusting your search.
                    @else
                    There are no listings to display in this view.
                    @endif
                </p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- DELETE REASON MODAL (SUPER ADMIN) --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeDeleteModal"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h3 class="text-lg font-bold text-red-800">Delete Shop</h3>
                <p class="text-xs text-red-600 mt-0.5">The owner will be notified with your reason.</p>
            </div>

            <div class="px-6 py-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Reason for deletion <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-2">
                    This will be included in the notification sent to the shop owner.
                </p>
                <textarea wire:model="deleteReason" rows="4" maxlength="500"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('deleteReason') border-red-500 @enderror"
                    placeholder="e.g. Violation of platform policy, invalid business permit..."></textarea>
                @error('deleteReason')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400 text-right">
                    {{ strlen($deleteReason) }}/500
                </p>
            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex justify-end gap-2">
                <button wire:click="closeDeleteModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Cancel
                </button>
                <button wire:click="delete" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-sm disabled:opacity-50">
                    Delete Shop
                </button>
            </div>
        </div>
    </div>
    @endif
</div>