<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📋 Seller Applications</h1>
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
                                    <p class="font-medium text-gray-800">{{ $app->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $app->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-800">{{ $app->shop_name }}</td>
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
                                No seller applications yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Details Modal -->
        @if($showDetails && $selectedApplication)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[85vh] overflow-y-auto p-6 animate-fadeIn">

                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">📄 Application Details</h2>
                    <button wire:click="closeDetails" class="text-gray-400 hover:text-gray-600 transition">
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
                        <p class="font-medium text-gray-800 text-sm">{{ $selectedApplication->user->name }}</p>
                        <p class="text-xs text-gray-600">{{ $selectedApplication->user->email }}</p>
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
                        📄 View Business Permit
                    </a>
                    @else
                    <p class="text-sm text-gray-400">No file uploaded</p>
                    @endif
                </div>

                <!-- ✅ Valid Government ID -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Valid Government ID</p>
                    @if($selectedApplication->valid_id_path)
                    <a href="{{ asset('storage/' . $selectedApplication->valid_id_path) }}" target="_blank"
                        class="text-amber-600 hover:text-amber-700 font-medium text-sm inline-flex items-center gap-1">
                        🪪 View Valid ID
                    </a>
                    @else
                    <p class="text-sm text-gray-400">No file uploaded</p>
                    @endif
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
                        <button wire:click="reject({{ $selectedApplication->id }})"
                            class="px-4 py-2 bg-red-600 text-black rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-md hover:shadow-lg">
                            ❌ Confirm Reject
                        </button>
                        <button wire:click="closeDetails"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                            Cancel
                        </button>
                    </div>
                    @else
                    <div class="flex gap-3">
                        <button wire:click="approve({{ $selectedApplication->id }})"
                            class="flex-1 px-4 py-3 bg-green-600 text-black rounded-lg hover:bg-green-700 transition text-sm font-medium shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <span class="text-lg">✅</span>
                            Approve
                        </button>
                        <button wire:click="startReject"
                            class="flex-1 px-4 py-3 bg-red-600 text-black rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <span class="text-lg">❌</span>
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