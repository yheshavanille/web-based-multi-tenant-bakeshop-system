<div>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🚀</div>
            <h1 class="text-3xl font-bold text-gray-800">Start Selling on BakeshopHub</h1>
            <p class="text-gray-500 mt-2">Reach more customers and grow your bakeshop business.</p>
        </div>

        @if($hasPendingApplication)
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg">
            <p class="font-medium">⏳ Application Pending</p>
            <p class="text-sm">You already have a pending seller application. Please wait for admin approval.</p>
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="text-sm text-amber-600 hover:underline mt-2 inline-block">
                ← Back to Dashboard
            </a>
        </div>
        @elseif($isAlreadySeller)
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            <p class="font-medium">✅ You're already a seller!</p>
            <p class="text-sm">You can manage your shop from the dashboard.</p>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="text-sm text-amber-600 hover:underline mt-2 inline-block">
                Go to Shop Dashboard →
            </a>
        </div>
        @else
        <!-- Benefits -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-3xl mb-2">👥</div>
                <p class="font-medium text-gray-800">Reach More Customers</p>
                <p class="text-sm text-gray-500">Connect with customers in Victorias City</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-3xl mb-2">📦</div>
                <p class="font-medium text-gray-800">Easy Management</p>
                <p class="text-sm text-gray-500">Manage products, orders, and branches</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-3xl mb-2">💳</div>
                <p class="font-medium text-gray-800">Secure Payments</p>
                <p class="text-sm text-gray-500">GCash, PayMaya, and Cash on Pickup</p>
            </div>
        </div>

        <!-- Requirements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 Requirements</h2>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-center gap-2">✅ Must be at least 18 years old</li>
                <li class="flex items-center gap-2">✅ Valid Government ID</li>
                <li class="flex items-center gap-2">✅ Valid Business Permit from LGU</li>
                <li class="flex items-center gap-2">✅ Valid contact number and address</li>
                <li class="flex items-center gap-2">✅ Bakeshop located in Victorias City</li>
            </ul>
        </div>

        <!-- Start Button -->
        <div class="text-center">
            <a href="{{ route('livewire.customer.seller-registration') }}"
                class="inline-flex items-center px-8 py-4 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition text-lg font-semibold shadow-md hover:shadow-lg">
                Start Registration →
            </a>
            <p class="text-sm text-gray-400 mt-3">This will take about 5 minutes</p>
        </div>
        @endif
    </div>
</div>