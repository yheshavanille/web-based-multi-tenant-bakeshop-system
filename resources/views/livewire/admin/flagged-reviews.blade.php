<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Flagged Reviews</h1>
                <p class="text-sm text-gray-500">Moderate reviews flagged by shop owners</p>
            </div>
            <a href="{{ route('livewire.admin.admin-dashboard') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg shadow-sm">
            {{ session('message') }}
        </div>
        @endif

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button wire:click="setTab('service')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'service' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                Service Reviews ({{ $serviceReviews->count() }})
            </button>
            <button wire:click="setTab('product')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'product' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                Product Reviews ({{ $productReviews->count() }})
            </button>
        </div>

        @if($activeTab === 'service')
        <div class="space-y-4">
            @forelse($serviceReviews as $review)
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap mb-3">
                            <span class="text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">🚩
                                Flagged</span>
                            <span class="text-xs text-gray-500">by <span class="font-medium text-gray-700">{{
                                    $review->flaggedBy->name ?? 'Unknown' }}</span> (owner)</span>
                            <span class="text-xs text-gray-400">· {{ $review->flagged_at?->diffForHumans() }}</span>
                        </div>

                        <p class="text-sm text-gray-800">
                            <span class="font-semibold">Shop:</span> {{ $review->shop->shop_name ?? 'N/A' }}
                            @if($review->branch)
                            · <span class="font-semibold">Branch:</span> {{ $review->branch->name }}
                            @endif
                        </p>

                        <p class="text-sm text-gray-800 mt-1">
                            <span class="font-semibold">Reviewer:</span> {{ $review->customer->name ?? 'Anonymous' }}
                            ({{ $review->customer->email ?? 'N/A' }})
                        </p>

                        <div class="flex items-center gap-3 mt-2 text-sm">
                            <span class="text-amber-500">{{ str_repeat('⭐', $review->rating) }}</span>
                            <span class="text-gray-500">Service: {{ $review->rating }}/5</span>
                            @if($review->employee_rating)
                            <span class="text-gray-500">Employee: {{ $review->employee_rating }}/5</span>
                            @endif
                        </div>

                        @if($review->review)
                        <p class="text-sm text-gray-700 italic mt-3">"{{ $review->review }}"</p>
                        @endif

                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-xs font-semibold text-amber-800">Flag reason: {{ $review->flag_reason }}</p>
                            @if($review->flag_notes)
                            <p class="text-xs text-gray-700 mt-1 italic">"{{ $review->flag_notes }}"</p>
                            @endif
                        </div>
                    </div>

                    <button wire:click="openModerationModal({{ $review->id }}, 'service')"
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium flex-shrink-0">
                        Review
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">

                <p>No flagged service reviews.</p>
            </div>
            @endforelse
        </div>
        @endif

        @if($activeTab === 'product')
        <div class="space-y-4">
            @forelse($productReviews as $review)
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap mb-3">
                            <span class="text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">🚩
                                Flagged</span>
                            <span class="text-xs text-gray-500">by <span class="font-medium text-gray-700">{{
                                    $review->flaggedBy->name ?? 'Unknown' }}</span> (owner)</span>
                            <span class="text-xs text-gray-400">· {{ $review->flagged_at?->diffForHumans() }}</span>
                        </div>

                        <p class="text-sm text-gray-800">
                            <span class="font-semibold">Shop:</span> {{ $review->shop->shop_name ?? 'N/A' }}
                            · <span class="font-semibold">Product:</span> {{ $review->product->name ?? 'N/A' }}
                        </p>

                        <p class="text-sm text-gray-800 mt-1">
                            <span class="font-semibold">Reviewer:</span> {{ $review->customer->name ?? 'Anonymous' }}
                            ({{ $review->customer->email ?? 'N/A' }})
                        </p>

                        <div class="flex items-center gap-2 mt-2 text-sm">
                            <span class="text-amber-500">{{ str_repeat('⭐', $review->rating) }}</span>
                            <span class="text-gray-500">{{ $review->rating }}/5</span>
                        </div>

                        @if($review->review)
                        <p class="text-sm text-gray-700 italic mt-3">"{{ $review->review }}"</p>
                        @endif

                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-xs font-semibold text-amber-800">Flag reason: {{ $review->flag_reason }}</p>
                            @if($review->flag_notes)
                            <p class="text-xs text-gray-700 mt-1 italic">"{{ $review->flag_notes }}"</p>
                            @endif
                        </div>
                    </div>

                    <button wire:click="openModerationModal({{ $review->id }}, 'product')"
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium flex-shrink-0">
                        Review
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">

                <p>No flagged product reviews.</p>
            </div>
            @endforelse
        </div>
        @endif
    </div>

    <!-- Moderation Modal -->
    @if($showModerationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModerationModal"></div>

        <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50">
                <h3 class="text-lg font-bold text-gray-800">Moderate Review</h3>
                <p class="text-xs text-gray-500 mt-0.5">Choose an action for this flagged review.</p>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Moderator notes <span class="text-xs text-gray-400">(optional)</span>
                    </label>
                    <textarea wire:model="moderator_notes" rows="3" maxlength="500"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                        placeholder="Why are you making this decision?"></textarea>
                    @error('moderator_notes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <button wire:click="decide('keep')" wire:loading.attr="disabled"
                        class="w-full px-4 py-3 bg-green-50 border border-green-200 hover:bg-green-100 rounded-lg transition text-left flex items-start gap-3 disabled:opacity-60">
                        <span class="text-xl">✅</span>
                        <div>
                            <p class="font-semibold text-green-800 text-sm">Keep Review</p>
                            <p class="text-xs text-green-700">The review stays visible. The owner will be notified.</p>
                        </div>
                    </button>

                    <button wire:click="decide('remove')" wire:loading.attr="disabled"
                        class="w-full px-4 py-3 bg-red-50 border border-red-200 hover:bg-red-100 rounded-lg transition text-left flex items-start gap-3 disabled:opacity-60">
                        <span class="text-xl">❌</span>
                        <div>
                            <p class="font-semibold text-red-800 text-sm">Remove Review</p>
                            <p class="text-xs text-red-700">The review is hidden from customers. The customer can still
                                submit new reviews.</p>
                        </div>
                    </button>

                    <button wire:click="decide('ban')" wire:loading.attr="disabled"
                        onclick="confirm('Ban this reviewer from leaving any future reviews? This will also remove the current review.') || event.stopImmediatePropagation()"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 hover:bg-gray-100 rounded-lg transition text-left flex items-start gap-3 disabled:opacity-60">
                        <span class="text-xl">🚫</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Remove & Ban Reviewer</p>
                            <p class="text-xs text-gray-600">Removes the review and blocks the customer from leaving any
                                reviews platform-wide.</p>
                        </div>
                    </button>
                </div>
            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button wire:click="closeModerationModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif
</div>