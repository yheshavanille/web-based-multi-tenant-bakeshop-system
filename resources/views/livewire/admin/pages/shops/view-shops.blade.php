<div>
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $showDeleted ? '🗑️ Deleted Bakeshops' : '🏪 View Bakeshops' }}
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
                    class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $showDeleted ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-gray-600 text-white hover:bg-gray-700' }}">
                    {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                </button>
                <a href="{{ route('livewire.admin.admin-dashboard') }}"
                    class="rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">
                    ← Back
                </a>
            </div>
        </div>

        @if (session()->has('message'))
        <div
            class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-600 text-white">✓</span>
            {{ session('message') }}
        </div>
        @endif

        <div class="mb-5 flex items-center justify-between pb-3">
            <div>
                <p class="text-sm font-semibold text-slate-800">{{ $showDeleted ? 'Archived listings' : 'Active
                    listings' }}</p>
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
                        <span class="text-5xl">🏪</span>
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
                        <p class="mt-1 text-sm text-slate-500">{{ $shop->address ?: 'Address not provided' }}</p>
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
                            class="flex-1 rounded-lg bg-emerald-50 px-3 py-2.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                            🔄 Restore
                        </button>
                        <button wire:click="forceDelete({{ $shop->id }})"
                            onclick="confirm('Permanently delete this shop? This cannot be undone.') || event.stopImmediatePropagation()"
                            class="flex-1 rounded-lg bg-rose-50 px-3 py-2.5 text-xs font-bold text-rose-700 transition hover:bg-rose-100">
                            🗑️ Delete forever
                        </button>
                        @else
                        <a href="{{ route('livewire.admin.pages.shops.shop-details', ['shopId' => $shop->id]) }}"
                            class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-blue-700">
                            🏪 View Shop Details
                        </a>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <div
                class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-4xl">🏪
                </div>
                <p class="mt-5 text-lg font-semibold text-slate-800">{{ $showDeleted ? 'No deleted shops found' : 'No
                    bakeshops available' }}</p>
                <p class="mt-1 text-sm text-slate-500">There are no listings to display in this view.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>