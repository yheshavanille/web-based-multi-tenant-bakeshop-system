<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Seller Applications</h1>
                <p class="text-sm text-gray-500">Review and approve/reject seller registrations</p>
            </div>
            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium shadow-sm">
                {{ $applications->where('status', 'pending')->count() }} Pending
            </span>
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

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="relative">
                        <select wire:model.live="statusFilter"
                            class="appearance-none w-full px-3 py-2 pr-9 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white cursor-pointer">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                            style="position: absolute;">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live="search"
                            placeholder="Search by applicant name, email, or shop name..."
                            class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        @if(!empty($search))
                        <button wire:click="clearSearch" type="button"
                            class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                            style="position: absolute;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                    @if(!empty($search))
                    <p class="mt-1 text-xs text-gray-500">
                        Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
                        <span class="text-gray-400">({{ $applications->count() }} found)</span>
                    </p>
                    @endif
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Applications List -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Applicant</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Shop Name</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Submitted</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($applications as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $app->user?->name ?? 'User Deleted' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $app->user?->email ?? 'No email available' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-800">{{ $app->shop_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ $app->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $app->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $app->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="viewDetails({{ $app->id }})"
                                    class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                @if(!empty($search))
                                No applications found matching "<span class="font-medium text-amber-600">{{ $search
                                    }}</span>"
                                @else
                                No seller applications yet.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Details Modal -->
        @if($showDetails && $selectedApplication)
        <div class="fixed inset-0 bg-gray-900/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[85vh] overflow-y-auto p-6">

                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 inline-flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Application Details
                    </h2>
                    <button wire:click="closeDetails" wire:loading.attr="disabled"
                        class="text-gray-400 hover:text-gray-600 transition disabled:opacity-40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Applicant Info -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Applicant</p>
                        <p class="font-medium text-gray-800 text-sm">
                            {{ $selectedApplication->user?->name ?? 'User Deleted' }}
                        </p>
                        <p class="text-xs text-gray-600">
                            {{ $selectedApplication->user?->email ?? 'No email available' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Shop Name</p>
                        <p class="font-medium text-gray-800 text-sm">{{ $selectedApplication->shop_name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                        <p class="text-sm text-gray-800">{{ $selectedApplication->shop_address }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Contact</p>
                        <p class="text-sm text-gray-800">{{ $selectedApplication->contact_number }}</p>
                    </div>
                </div>

                @if($selectedApplication->shop_description)
                <div class="bg-gray-50 rounded-lg p-3 mb-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Shop Description</p>
                    <p class="text-sm text-gray-800">{{ $selectedApplication->shop_description }}</p>
                </div>
                @endif

                <!-- Business Permit -->
                <div class="bg-gray-50 rounded-lg p-3 mb-3 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Business Permit</p>
                    @if($selectedApplication->business_permit)
                    <a href="{{ asset('storage/' . $selectedApplication->business_permit) }}" target="_blank"
                        class="text-amber-600 hover:text-amber-700 font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View Business Permit
                    </a>
                    @else
                    <p class="text-sm text-gray-400">No file uploaded</p>
                    @endif
                </div>

                <!-- Valid Government ID -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Valid Government ID</p>
                    @if($selectedApplication->valid_id_path)
                    <a href="{{ asset('storage/' . $selectedApplication->valid_id_path) }}" target="_blank"
                        class="text-amber-600 hover:text-amber-700 font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        View Valid ID
                    </a>
                    @else
                    <p class="text-sm text-gray-400">No file uploaded</p>
                    @endif
                </div>

                <!-- Requirements Checklist -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <h4 class="text-sm font-semibold text-gray-700">Requirements Checklist</h4>
                        <span class="text-xs text-gray-400 ml-auto">Check all that are verified</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        {{-- wire:model.live so the progress bar updates instantly --}}
                        <label
                            class="flex items-center gap-3 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" wire:model.live="requirements.valid_id"
                                class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                    Valid Government ID
                                </p>
                                <p class="text-xs text-gray-400 inline-flex items-center gap-1">
                                    Uploaded:
                                    @if($selectedApplication->valid_id_path)
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Yes
                                    @else
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    No
                                    @endif
                                </p>
                            </div>
                            @if($selectedApplication->valid_id_path)
                            <a href="{{ asset('storage/' . $selectedApplication->valid_id_path) }}" target="_blank"
                                class="text-xs text-amber-600 hover:text-amber-700">
                                View
                            </a>
                            @endif
                        </label>

                        <label
                            class="flex items-center gap-3 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" wire:model.live="requirements.business_permit"
                                class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Business Permit
                                </p>
                                <p class="text-xs text-gray-400 inline-flex items-center gap-1">
                                    Uploaded:
                                    @if($selectedApplication->business_permit)
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Yes
                                    @else
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    No
                                    @endif
                                </p>
                            </div>
                            @if($selectedApplication->business_permit)
                            <a href="{{ asset('storage/' . $selectedApplication->business_permit) }}" target="_blank"
                                class="text-xs text-amber-600 hover:text-amber-700">
                                View
                            </a>
                            @endif
                        </label>

                        <label
                            class="flex items-center gap-3 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" wire:model.live="requirements.shop_name"
                                class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Shop Name
                                </p>
                                <p class="text-xs text-gray-400 inline-flex items-center gap-1">
                                    Provided:
                                    @if($selectedApplication->shop_name)
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Yes
                                    @else
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    No
                                    @endif
                                </p>
                            </div>
                        </label>

                        <label
                            class="flex items-center gap-3 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" wire:model.live="requirements.shop_address"
                                class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Shop Address
                                </p>
                                <p class="text-xs text-gray-400 inline-flex items-center gap-1">
                                    Provided:
                                    @if($selectedApplication->shop_address)
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Yes
                                    @else
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    No
                                    @endif
                                </p>
                            </div>
                        </label>

                        <label
                            class="flex items-center gap-3 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:bg-gray-50 transition col-span-1 sm:col-span-2">
                            <input type="checkbox" wire:model.live="requirements.contact_number"
                                class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Contact Number
                                </p>
                                <p class="text-xs text-gray-400 inline-flex items-center gap-1">
                                    Provided:
                                    @if($selectedApplication->contact_number)
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Yes
                                    @else
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    No
                                    @endif
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Progress -->
                    @php
                    $checked = count(array_filter($requirements));
                    $total = count($requirements);
                    $percentage = $total > 0 ? round(($checked / $total) * 100) : 0;
                    $allChecked = $checked === $total;
                    @endphp
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">{{ $checked }} of {{ $total }} requirements checked</span>
                            <span class="font-medium {{ $allChecked ? 'text-green-600' : 'text-amber-600' }} inline-flex items-center gap-1">
                                @if($allChecked)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                All requirements met!
                                @else
                                {{ $percentage }}% complete
                                @endif
                            </span>
                        </div>
                        <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-300 {{ $allChecked ? 'bg-green-500' : 'bg-amber-500' }}"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- CUSTOM NOTE FIELD -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4 shadow-sm border border-gray-100">
                    <label class="text-sm font-medium text-gray-700 mb-1 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Custom Note <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-2">Add a personal message to include in the notification.</p>
                    <textarea wire:model="custom_note" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm shadow-sm"
                        placeholder="Add a custom note for the seller..."></textarea>
                </div>

                <!-- Status -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                    <span class="inline-flex px-2.5 py-1 text-sm font-medium rounded-full shadow-sm
                            {{ $selectedApplication->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $selectedApplication->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $selectedApplication->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($selectedApplication->status) }}
                    </span>
                </div>

                <!-- Actions -->
                @if($selectedApplication->status === 'pending')
                <div class="flex flex-col gap-3 pt-2 border-t border-gray-200">
                    @if($rejecting)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                        <textarea wire:model="rejection_reason" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm shadow-sm"
                            placeholder="Why is this application being rejected?"></textarea>
                        @error('rejection_reason')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex gap-3">
                        {{-- Confirm Reject — disabled during processing --}}
                        <button wire:click="reject({{ $selectedApplication->id }})" wire:loading.attr="disabled"
                            wire:target="reject({{ $selectedApplication->id }})"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-md hover:shadow-lg disabled:opacity-60 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="reject({{ $selectedApplication->id }})" class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Confirm Reject
                            </span>
                            <span wire:loading wire:target="reject({{ $selectedApplication->id }})">Rejecting...</span>
                        </button>

                        <button wire:click="closeDetails" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium disabled:opacity-60">
                            Cancel
                        </button>
                    </div>
                    @else
                    <div class="flex gap-3">
                        {{-- Approve — disabled during processing --}}
                        <button wire:click="approve({{ $selectedApplication->id }})" wire:loading.attr="disabled"
                            wire:target="approve({{ $selectedApplication->id }})"
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium shadow-md hover:shadow-lg flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed {{ !$allChecked ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$allChecked ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="approve({{ $selectedApplication->id }})">
                                Approve
                                @if(!$allChecked)
                                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Check all requirements
                                    first</span>
                                @endif
                            </span>
                            <span wire:loading wire:target="approve({{ $selectedApplication->id }})">
                                Approving...
                            </span>
                        </button>

                        {{-- Reject (start form) — disabled during processing --}}
                        <button wire:click="startReject" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-md hover:shadow-lg flex items-center justify-center gap-2 disabled:opacity-60">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Reject
                        </button>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
