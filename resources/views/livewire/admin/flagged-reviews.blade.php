<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Flagged Reviews</h1>
                <p class="text-sm text-gray-500">Moderate reviews flagged by shop owners</p>
            </div>
            <a href="{{ route('livewire.admin.admin-dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
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
            <button wire:click="setTab('banned')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'banned' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                Banned Reviewers ({{ $bannedReviewers->count() }})
            </button>
        </div>

        @if($activeTab === 'service')
        <div class="space-y-4">
            @forelse($serviceReviews as $review)
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap mb-3">
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9">
                                    </path>
                                </svg>
                                Flagged
                            </span>
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
                            <span class="inline-flex items-center gap-0.5 text-amber-500">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    @endfor
                            </span>
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
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9">
                                    </path>
                                </svg>
                                Flagged
                            </span>
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
                            <span class="inline-flex items-center gap-0.5 text-amber-500">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    @endfor
                            </span>
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

        {{-- Banned Reviewers Tab --}}
        @if($activeTab === 'banned')
        <div class="space-y-4">
            @forelse($bannedReviewers as $user)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap mb-3">
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                    </path>
                                </svg>
                                Banned from reviewing
                            </span>
                            <span class="text-xs text-gray-400">· banned {{ $user->review_banned_at?->diffForHumans()
                                }}</span>
                        </div>

                        <p class="text-sm text-gray-800">
                            <span class="font-semibold">Name:</span> {{ $user->name }}
                        </p>
                        <p class="text-sm text-gray-800 mt-1">
                            <span class="font-semibold">Email:</span> {{ $user->email }}
                        </p>
                        @if($user->phone)
                        <p class="text-sm text-gray-800 mt-1">
                            <span class="font-semibold">Phone:</span> {{ $user->phone }}
                        </p>
                        @endif
                        <p class="text-sm text-gray-800 mt-1">
                            <span class="font-semibold">Banned on:</span>
                            {{ $user->review_banned_at?->format('M d, Y h:i A') }}
                        </p>

                        @if($user->review_ban_reason)
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-xs font-semibold text-red-800">Ban reason:</p>
                            <p class="text-xs text-gray-700 mt-1 italic">"{{ $user->review_ban_reason }}"</p>
                        </div>
                        @endif
                    </div>

                    <button wire:click="unbanReviewer({{ $user->id }})" wire:loading.attr="disabled"
                        onclick="confirm('Unban {{ $user->name }}? They will be able to leave reviews again.') || event.stopImmediatePropagation()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium flex-shrink-0 disabled:opacity-60">
                        Unban
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">
                <p>No banned reviewers. Everyone is welcome to leave reviews.</p>
            </div>
            @endforelse
        </div>
        @endif
    </div>

    <!-- Moderation Modal (unchanged) -->
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
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-800 text-sm">Keep Review</p>
                            <p class="text-xs text-green-700">The review stays visible. The owner will be notified.</p>
                        </div>
                    </button>

                    <button wire:click="decide('remove')" wire:loading.attr="disabled"
                        class="w-full px-4 py-3 bg-red-50 border border-red-200 hover:bg-red-100 rounded-lg transition text-left flex items-start gap-3 disabled:opacity-60">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-800 text-sm">Remove Review</p>
                            <p class="text-xs text-red-700">The review is hidden from customers. The customer can still
                                submit new reviews.</p>
                        </div>
                    </button>

                    <button wire:click="decide('ban')" wire:loading.attr="disabled"
                        onclick="confirm('Ban this reviewer from leaving any future reviews? This will also remove the current review.') || event.stopImmediatePropagation()"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 hover:bg-gray-100 rounded-lg transition text-left flex items-start gap-3 disabled:opacity-60">
                        <svg class="w-6 h-6 text-gray-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                            </path>
                        </svg>
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