<div>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-slate-100 sm:text-2xl">Business Permits</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                    Review permit renewals and monitor current permit expiry dates.
                </p>
            </div>
            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                {{ $pendingReviewCount }} pending review
            </span>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
            {{ session('message') }}
        </div>
        @endif

        <div class="mb-4">
            <label for="permitSearch" class="sr-only">Search shops</label>
            <input id="permitSearch" type="search" wire:model.live.debounce.300ms="search"
                placeholder="Search shops or owners…"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 sm:max-w-md">
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] divide-y divide-gray-200 text-sm dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Shop / Owner</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Current Permit</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Expiry</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Renewal Submission</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($shops as $shop)
                        @php
                        $validityStyles = [
                            'valid' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                            'expiring_soon' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                            'expired' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                            'not_submitted' => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300',
                        ];
                        $validityLabels = [
                            'valid' => 'Valid',
                            'expiring_soon' => 'Expiring soon',
                            'expired' => 'Expired',
                            'not_submitted' => 'Not submitted',
                        ];
                        @endphp
                        <tr class="align-top hover:bg-gray-50 dark:hover:bg-slate-700/40">
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 dark:text-slate-100">{{ $shop->shop_name }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                                    {{ $shop->user?->name ?? 'Owner unavailable' }}
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $validityStyles[$shop->permit_validity_status] }}">
                                    {{ $validityLabels[$shop->permit_validity_status] }}
                                </span>
                                @if($shop->business_permit_path)
                                <a href="{{ route('livewire.admin.business-permits.document', ['shopId' => $shop->id, 'document' => 'current']) }}"
                                    target="_blank" rel="noopener"
                                    class="mt-2 block text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    View approved permit
                                </a>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-gray-600 dark:text-slate-300">
                                {{ $shop->business_permit_expires_at?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-4">
                                @if($shop->business_permit_review_status === 'pending_review')
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                                    Pending review
                                </span>
                                @elseif($shop->business_permit_review_status === 'rejected')
                                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/40 dark:text-red-200">
                                    Rejected
                                </span>
                                @else
                                <span class="text-gray-500 dark:text-slate-400">—</span>
                                @endif
                                @if($shop->pending_business_permit_path)
                                <a href="{{ route('livewire.admin.business-permits.document', ['shopId' => $shop->id, 'document' => 'pending']) }}"
                                    target="_blank" rel="noopener"
                                    class="mt-2 block text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    View submitted document
                                </a>
                                <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                                    Submitted expiry: {{ $shop->pending_business_permit_expires_at?->format('M d, Y') ?? 'Not provided' }}
                                </p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($shop->pending_business_permit_path && $shop->business_permit_review_status === 'pending_review')
                                <button type="button" wire:click="openReview({{ $shop->id }})"
                                    class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-blue-700">
                                    Review
                                </button>
                                @else
                                <span class="text-xs text-gray-400 dark:text-slate-500">No submission</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-slate-400">
                                No shops matched your search.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showReviewModal && $selectedShop)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" x-data>
        <button type="button" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeReview"
            aria-label="Close review"></button>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl dark:bg-slate-800 sm:p-6">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Review Business Permit</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ $selectedShop->shop_name }}</p>
                </div>
                <button type="button" wire:click="closeReview"
                    class="text-gray-400 transition hover:text-gray-700 dark:hover:text-slate-200" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <a href="{{ route('livewire.admin.business-permits.document', ['shopId' => $selectedShop->id, 'document' => 'pending']) }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400"
                target="_blank" rel="noopener">
                Open submitted permit
            </a>

            <div class="mt-4">
                <label for="reviewExpiryDate" class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                    Verify or correct expiry date
                </label>
                <input id="reviewExpiryDate" type="date" wire:model="reviewExpiryDate"
                    min="{{ today()->format('Y-m-d') }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                @error('reviewExpiryDate')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="rejectionNote" class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                    Rejection note (required to reject)
                </label>
                <textarea id="rejectionNote" wire:model="rejectionNote" rows="3" maxlength="500"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100"
                    placeholder="Explain what the owner needs to correct."></textarea>
                @error('rejectionNote')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-2">
                <button type="button" wire:click="closeReview"
                    class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                    Cancel
                </button>
                <button type="button" wire:click="reject" wire:loading.attr="disabled"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 disabled:opacity-50">
                    Reject
                </button>
                <button type="button" wire:click="approve" wire:loading.attr="disabled"
                    class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700 disabled:opacity-50">
                    Approve
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
