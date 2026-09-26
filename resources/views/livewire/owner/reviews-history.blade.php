<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Reviews History</h1>
                <p class="text-sm text-gray-500">View customer feedback for your bakeshop</p>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
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

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg shadow-sm">
            {{ session('error') }}
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

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select wire:model.live="branchFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select wire:model.live="ratingFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="all">All Ratings</option>
                        <option value="5">5 stars</option>
                        <option value="4">4 stars</option>
                        <option value="3">3 stars</option>
                        <option value="2">2 stars</option>
                        <option value="1">1 star</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Service Reviews Tab -->
        @if($activeTab === 'service')
        <div class="space-y-4">
            @forelse($serviceReviews as $review)
            @php
            $pic = $review->customer->profile_picture ?? null;
            $customerName = $review->customer->name ?? 'Anonymous';
            $initials = strtoupper(substr($customerName, 0, 2));
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            {{-- Profile picture if available, else initials --}}
                            @if($pic)
                            <img src="{{ asset('storage/' . $pic) }}" alt="{{ $customerName }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">
                            @else
                            <div
                                class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                {{ $initials }}
                            </div>
                            @endif

                            <div>
                                <p class="font-semibold text-gray-800">{{ $customerName }}</p>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            {{-- Status badges --}}
                            @if($review->moderation_status === 'pending_review')
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Flagged — awaiting review
                            </span>
                            @elseif($review->moderation_status === 'kept')
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Kept by Admin
                            </span>
                            @elseif($review->moderation_status === 'removed')
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                    </path>
                                </svg>
                                Removed by Admin
                            </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 mt-3 text-sm flex-wrap">
                            <span class="inline-flex items-center gap-1">
                                <span class="text-gray-500">Service:</span>
                                <span class="inline-flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)
                                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        @endif
                                        @endfor
                                </span>
                            </span>
                            @if($review->employee_rating)
                            <span class="inline-flex items-center gap-1">
                                <span class="text-gray-500">Employee:</span>
                                <span class="inline-flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++) @if($i <=$review->employee_rating)
                                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        @endif
                                        @endfor
                                </span>
                            </span>
                            @endif
                            @if($review->branch)
                            <span class="inline-flex items-center gap-1 text-gray-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $review->branch->name }}
                            </span>
                            @endif
                        </div>

                        @if($review->review)
                        <p class="text-sm text-gray-700 italic mt-3">"{{ $review->review }}"</p>
                        @endif

                        {{-- Flag metadata --}}
                        @if($review->flag_reason)
                        <div class="mt-3 p-2.5 bg-amber-50 border border-amber-100 rounded-lg text-xs">
                            <p class="text-amber-800"><span class="font-medium">Flag reason:</span> {{
                                $review->flag_reason }}</p>
                            @if($review->flag_notes)
                            <p class="text-gray-600 mt-0.5 italic">"{{ $review->flag_notes }}"</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Flag button --}}
                    @if($review->moderation_status === 'visible')
                    <button wire:click="openFlagModal({{ $review->id }}, 'service')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 rounded-lg transition flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9">
                            </path>
                        </svg>
                        Flag
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                </div>
                <p>No service reviews yet.</p>
            </div>
            @endforelse
        </div>
        @endif

        <!-- Product Reviews Tab -->
        @if($activeTab === 'product')
        <div class="space-y-4">
            @forelse($productReviews as $review)
            @php
            $pic = $review->customer->profile_picture ?? null;
            $customerName = $review->customer->name ?? 'Anonymous';
            $initials = strtoupper(substr($customerName, 0, 2));
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            {{-- Profile picture if available, else initials --}}
                            @if($pic)
                            <img src="{{ asset('storage/' . $pic) }}" alt="{{ $customerName }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">
                            @else
                            <div
                                class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                {{ $initials }}
                            </div>
                            @endif

                            <div>
                                <p class="font-semibold text-gray-800">{{ $customerName }}</p>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            {{-- Status badges --}}
                            @if($review->moderation_status === 'pending_review')
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Flagged — awaiting review
                            </span>
                            @elseif($review->moderation_status === 'kept')
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Kept by Admin
                            </span>
                            @elseif($review->moderation_status === 'removed')
                            <span
                                class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-800 font-medium px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                    </path>
                                </svg>
                                Removed by Admin
                            </span>
                            @endif
                        </div>

                        <p class="text-sm text-gray-700 mt-2">
                            <span class="font-medium">Product:</span> {{ $review->product->name ?? 'N/A' }}
                        </p>

                        <div class="flex items-center gap-2 mt-2 text-sm">
                            <span class="inline-flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    @endif
                                    @endfor
                            </span>
                            <span class="text-gray-500">({{ $review->rating }}/5)</span>
                        </div>

                        @if($review->review)
                        <p class="text-sm text-gray-700 italic mt-3">"{{ $review->review }}"</p>
                        @endif

                        {{-- Flag metadata --}}
                        @if($review->flag_reason)
                        <div class="mt-3 p-2.5 bg-amber-50 border border-amber-100 rounded-lg text-xs">
                            <p class="text-amber-800"><span class="font-medium">Flag reason:</span> {{
                                $review->flag_reason }}</p>
                            @if($review->flag_notes)
                            <p class="text-gray-600 mt-0.5 italic">"{{ $review->flag_notes }}"</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Flag button --}}
                    @if($review->moderation_status === 'visible')
                    <button wire:click="openFlagModal({{ $review->id }}, 'product')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 rounded-lg transition flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9">
                            </path>
                        </svg>
                        Flag
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p>No product reviews yet.</p>
            </div>
            @endforelse
        </div>
        @endif
    </div>

    <!-- FLAG MODAL -->
    @if($showFlagModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeFlagModal"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h3 class="text-lg font-bold text-red-800 inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9">
                        </path>
                    </svg>
                    Flag This Review
                </h3>
                <p class="text-xs text-red-600 mt-0.5">The Super Admin will review this flag.</p>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="flag_reason"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('flag_reason') border-red-500 @enderror">
                        <option value="">— Select a reason —</option>
                        <option value="Spam">Spam</option>
                        <option value="Fake review">Fake review</option>
                        <option value="Inappropriate language">Inappropriate language</option>
                        <option value="Competitor attack">Competitor attack</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('flag_reason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Additional notes <span class="text-xs text-gray-400">(optional)</span>
                    </label>
                    <textarea wire:model="flag_notes" rows="3" maxlength="500"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                        placeholder="Explain why you're flagging this review..."></textarea>
                    @error('flag_notes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                    The review will stay visible to customers until the Super Admin decides.
                </div>
            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex justify-end gap-2">
                <button wire:click="closeFlagModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Cancel
                </button>
                <button wire:click="submitFlag" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-sm disabled:opacity-60">
                    Submit Flag
                </button>
            </div>
        </div>
    </div>
    @endif
</div>