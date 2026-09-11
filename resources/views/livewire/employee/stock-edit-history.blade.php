<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('livewire.employee.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">📋 Stock Edit History</h1>
                        <p class="text-sm text-gray-500">
                            {{ $branch->name }} • {{ $stockHistories->count() }} total updates
                        </p>
                    </div>
                </div>
            </div>
            <a href="{{ route('livewire.employee.inventory') }}"
                class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                ← Manage Stock
            </a>
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
                <input type="text" wire:model.live="search" placeholder="Search by product name or notes..."
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
                <span class="text-gray-400">({{ $stockHistories->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Stock History Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($stockHistories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Old</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">New</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Changed By</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Notes</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($stockHistories as $history)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $history->product?->name ?? 'Product Unavailable' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $history->branch?->name ?? 'Branch Unavailable' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $history->old_stock }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $history->new_stock > $history->old_stock ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $history->new_stock < $history->old_stock ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $history->new_stock == $history->old_stock ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $history->new_stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $history->user?->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $history->notes ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $history->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                @if(!empty($search))
                <p>No stock updates found matching "<span class="font-medium text-amber-600">{{ $search }}</span>"</p>
                <p class="text-xs text-gray-400">Try adjusting your search.</p>
                @else
                <p>No stock updates for this branch yet.</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>