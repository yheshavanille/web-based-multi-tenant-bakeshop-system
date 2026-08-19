<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📊 Manage Stock</h1>
        <p class="text-sm text-gray-500">
            Update inventory for <span class="font-medium text-blue-600">{{ $branch->name }}</span>
        </p>
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
                                class="px-3 py-1 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition">
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
            <p>No products found for this branch.</p>
        </div>
        @endif
    </div>
</div>