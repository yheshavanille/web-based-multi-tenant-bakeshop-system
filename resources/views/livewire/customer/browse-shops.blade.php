<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        <!-- HEADER -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-800">
                Browse Bakeshops
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Browse and explore bakeshop listings.
            </p>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($shops as $shop)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div
                    class="h-48 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-6xl">🍰</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $shop->shop_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $shop->description ?? 'No description
                        available' }}</p>
                    <a href="{{ route('livewire.customer.view-products', $shop->id) }}"
                        class="mt-3 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Visit Shop →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                <p class="text-lg">No bakeshops available yet.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>