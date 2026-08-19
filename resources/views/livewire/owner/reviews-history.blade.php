<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📝 Reviews History</h1>
                <p class="text-sm text-gray-500">View all customer reviews for your shop</p>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                ← Back to Dashboard
            </a>
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
                        <option value="5">⭐ 5 Stars</option>
                        <option value="4">⭐ 4 Stars</option>
                        <option value="3">⭐ 3 Stars</option>
                        <option value="2">⭐ 2 Stars</option>
                        <option value="1">⭐ 1 Star</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button wire:click="setTab('service')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'service' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                🌟 Service Reviews
            </button>
            <button wire:click="setTab('product')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'product' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                📦 Product Reviews
            </button>
        </div>

        <!-- Service Reviews Table -->
        @if($activeTab === 'service')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($serviceReviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Rating</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Employee Rating</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Review</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($serviceReviews as $review)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $review->customer->name ?? 'Anonymous' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $review->branch->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="text-amber-500 text-sm">{{ str_repeat('⭐', $review->rating) }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $review->rating }})</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($review->employee_rating)
                                <span class="text-amber-500 text-sm">{{ str_repeat('⭐', $review->employee_rating)
                                    }}</span>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $review->review ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $review->created_at->format('M d, Y h:i A')
                                }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <p>No service reviews found.</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Product Reviews Table -->
        @if($activeTab === 'product')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($productReviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Rating</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Review</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($productReviews as $review)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $review->customer->name ?? 'Anonymous' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $review->product->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $review->order->branch->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="text-amber-500 text-sm">{{ str_repeat('⭐', $review->rating) }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $review->rating }})</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $review->review ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $review->order->order_number ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $review->created_at->format('M d, Y h:i A')
                                }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <p>No product reviews found.</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>