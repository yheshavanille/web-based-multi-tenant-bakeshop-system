<div>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-800 dark:text-slate-100 sm:text-2xl">Business Permit</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                Submit your current business permit or upload a renewal for admin review.
            </p>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
            {{ session('message') }}
        </div>
        @endif

        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-slate-100">Current Approved Permit</h2>
            @php
            $validityStyles = [
                'valid' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                'expiring_soon' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                'expired' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                'not_submitted' => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300',
            ];
            $validityLabels = [
                'valid' => 'Valid',
                'expiring_soon' => 'Expiring within 30 days',
                'expired' => 'Expired',
                'not_submitted' => 'No approved permit',
            ];
            @endphp
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $validityStyles[$shop->permit_validity_status] }}">
                    {{ $validityLabels[$shop->permit_validity_status] }}
                </span>
                @if($shop->business_permit_expires_at)
                <span class="text-sm text-gray-600 dark:text-slate-300">
                    Expires {{ $shop->business_permit_expires_at->format('M d, Y') }}
                </span>
                @endif
                @if($shop->business_permit_path)
                <a href="{{ route('livewire.owner.business-permit.document', ['document' => 'current']) }}"
                    target="_blank" rel="noopener"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    View approved permit
                </a>
                @endif
            </div>
        </div>

        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 dark:text-slate-100">Renewal Review</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                        {{ $shop->business_permit_review_status === 'pending_review'
                            ? 'Your latest permit submission is waiting for admin review.'
                            : ($shop->business_permit_review_status === 'rejected'
                                ? 'Your latest permit submission was rejected. Review the note and submit a replacement.'
                                : 'No renewal is waiting for review.') }}
                    </p>
                </div>
                @if($shop->business_permit_review_status === 'pending_review')
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                    Pending review
                </span>
                @elseif($shop->business_permit_review_status === 'rejected')
                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/40 dark:text-red-200">
                    Rejected
                </span>
                @endif
            </div>

            @if($shop->pending_business_permit_path)
            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                <a href="{{ route('livewire.owner.business-permit.document', ['document' => 'pending']) }}"
                    target="_blank" rel="noopener"
                    class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    View submitted permit
                </a>
                @if($shop->pending_business_permit_expires_at)
                <span class="text-gray-600 dark:text-slate-300">
                    Submitted expiry date: {{ $shop->pending_business_permit_expires_at->format('M d, Y') }}
                </span>
                @endif
            </div>
            @endif

            @if($shop->business_permit_review_note)
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                <span class="font-semibold">Admin note:</span> {{ $shop->business_permit_review_note }}
            </div>
            @endif
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-slate-100">Submit a Permit or Renewal</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                Enter the expiry date printed on the document. An admin will verify it before the permit becomes active.
            </p>

            <form wire:submit.prevent="submitRenewal" class="mt-5 space-y-4">
                <div>
                    <label for="permitFile" class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                        Business permit file
                    </label>
                    <input id="permitFile" type="file" wire:model="permitFile" accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:font-medium file:text-amber-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:file:bg-amber-900/30 dark:file:text-amber-300">
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">PDF, JPG, or PNG. Maximum size: 5 MB.</p>
                    @error('permitFile')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="permitExpiryDate" class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                        Permit expiry date
                    </label>
                    <input id="permitExpiryDate" type="date" wire:model="permitExpiryDate"
                        min="{{ today()->format('Y-m-d') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 sm:max-w-xs">
                    @error('permitExpiryDate')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div wire:loading wire:target="permitFile" class="text-sm text-gray-500 dark:text-slate-400">
                    Uploading permit…
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-50">
                    Submit for Review
                </button>
            </form>
        </div>
    </div>
</div>
