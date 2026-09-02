<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">✏️ Product Edit History</h1>
                <p class="text-sm text-gray-500">View all product edit history</p>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search by product name..."
                    class="w-full pl-10 pr-10 h-10 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($histories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Field</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Old Value</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">New Value</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Updated By</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($histories as $history)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $history->product->name }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    {{ $history->field === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $history->field === 'name' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $history->field === 'price' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $history->field === 'category_id' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $history->field === 'description' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $history->field === 'image_url' ? 'bg-pink-100 text-pink-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $history->field)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-sm">
                                @if($history->field === 'price')
                                ₱{{ number_format($history->old_value ?? 0, 2) }}
                                @elseif($history->field === 'image_url')
                                <span class="text-xs text-gray-400">{{ $history->old_value ? 'Old image' : 'No image'
                                    }}</span>
                                @else
                                {{ $history->old_value ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm">
                                @if($history->field === 'price')
                                ₱{{ number_format($history->new_value ?? 0, 2) }}
                                @elseif($history->field === 'image_url')
                                <span class="text-xs text-green-600">{{ $history->new_value ? 'New image' : 'Removed'
                                    }}</span>
                                @elseif($history->field === 'created')
                                <span class="text-xs text-green-600">Product created</span>
                                @else
                                {{ $history->new_value ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $history->user->name ?? 'System' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <span class="text-4xl block mb-2">📭</span>
                <p>No product edit history found.</p>
            </div>
            @endif
        </div>
    </div>
</div>