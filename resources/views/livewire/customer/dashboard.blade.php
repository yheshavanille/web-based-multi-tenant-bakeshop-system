<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-amber-50 to-white rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-gray-600 mt-2">Where every bread, cake, and pastry is made fresh with love and quality
            ingredients.</p>
        <a href="{{ route('livewire.customer.browse-shops') }}"
            class="inline-flex items-center mt-4 px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
            Explore Bakeshops →
        </a>
    </div>

    <!-- Featured Shops -->
    @if($featuredShops->count() > 0)
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">🏪 Featured Bakeshops</h2>
            <a href="{{ route('livewire.customer.browse-shops') }}"
                class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                View All →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredShops as $shop)
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div
                    class="h-32 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                    @if($shop->shop_image)
                    <img src="{{ asset($shop->shop_image) }}" alt="{{ $shop->shop_name }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-4xl">🍰</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900">{{ $shop->shop_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $shop->address ?? 'Victorias City' }}</p>
                    <a href="{{ route('livewire.customer.view-products', $shop->id) }}"
                        class="mt-3 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Visit Shop →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Features -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">🏪</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Multiple Shops</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">📍</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Easy Pickup</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">🥖</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Fresh Daily</p>
        </div>
        <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <span class="text-2xl">💳</span>
            <p class="text-sm font-medium text-gray-900 mt-1">Secure Payments</p>
        </div>
    </div>

    <!-- Start Selling Banner -->
    @if(!auth()->user()->hasRole('owner'))
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">🚀 Turn your passion into business!</h3>
                <p class="text-sm text-gray-600">Register your bakeshop and reach more customers.</p>
            </div>
            <a href="#"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                Start Selling →
            </a>
        </div>
    </div>
    @endif
</div>