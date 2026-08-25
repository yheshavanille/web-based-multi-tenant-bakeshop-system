<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📊 Manage Stock</h1>
                <p class="text-sm text-gray-500">
                    Update inventory for <span class="font-medium text-amber-600">{{ $branch->name }}</span>
                </p>
            </div>
            <a href="{{ route('livewire.employee.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Dashboard
            </a>
        </div>
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
            <input type="text" wire:model.live="search" placeholder="Search products by name or description..."
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
        @if(!empty($search))
        <p class="mt-1 text-xs text-gray-500">
            Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
            <span class="text-gray-400">({{ $products->count() }} found)</span>
        </p>
        @endif
    </div>

    @if (session()->has('message'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
        {{ session('message') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Category</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Current Stock</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">New Stock</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Notes</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($products as $product)
                    @php
                    $currentStock = $product->branches->firstWhere('id', $branch->id)?->pivot->stock ?? 0;
                    @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $currentStock > 5 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $currentStock <= 5 && $currentStock > 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $currentStock <= 0 ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $currentStock }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" wire:model="stockUpdates.{{ $product->id }}" min="0"
                                class="w-20 px-2 py-1 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" wire:model="notes.{{ $product->id }}" placeholder="Optional note"
                                class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="updateStock({{ $product->id }})"
                                class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded-lg transition">
                                Update
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            @if(!empty($search))
            <p>No products found matching "<span class="font-medium text-amber-600">{{ $search }}</span>"</p>
            <p class="text-xs text-gray-400">Try adjusting your search.</p>
            @else
            <p>No products found for this branch.</p>
            @endif
        </div>
        @endif
    </div>
</div>