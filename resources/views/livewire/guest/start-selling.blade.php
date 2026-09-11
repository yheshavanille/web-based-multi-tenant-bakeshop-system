<div>
    <div class="max-w-3xl mx-auto px-4 pt-16 pb-8 sm:py-8">

        <!-- Header -->
        <div class="text-center mb-10">
            <div class="mb-4" style="font-size: 6rem; line-height: 1;">🍰
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Start Selling on</h1>
            <h2 class="text-2xl font-bold text-amber-600">Web-based Multi-Tenant Bakeshop System</h2>
            <p class="text-gray-500 mt-3 max-w-md mx-auto">Reach more customers and grow your bakeshop business.</p>
        </div>

        @if(!Auth::check())
        <div class="mb-6 p-6 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl shadow-sm">
            <div class="flex flex-col items-center gap-2 text-center">
                <span class="text-2xl">🔐</span>
                <div>
                    <p class="font-semibold text-gray-800">Please Login or Register First</p>
                    <p class="text-sm text-gray-600">You need to be logged in to start selling.</p>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-3 mt-4">
                <a href="{{ route('livewire.auth.login') }}?start_selling=true"
                    class="text-sm bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition inline-block font-medium shadow-sm hover:shadow">
                    Login Now →
                </a>
                <a href="{{ route('livewire.auth.register') }}?start_selling=true"
                    class="text-sm bg-white border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition inline-block font-medium">
                    Create Account →
                </a>
            </div>
        </div>
        @elseif($hasPendingApplication)
        <div class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <div>
                    <p class="font-semibold text-gray-800">Application Pending</p>
                    <p class="text-sm text-gray-600">You already have a pending seller application. Please wait for
                        admin approval.</p>
                </div>
            </div>
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="text-sm text-amber-600 hover:underline mt-3 inline-block">
                ← Back to Dashboard
            </a>
        </div>
        @elseif($isAlreadySeller)
        <div class="mb-6 p-6 bg-green-50 border border-green-200 rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-semibold text-gray-800">You're already a seller!</p>
                    <p class="text-sm text-gray-600">You can manage your shop from the dashboard.</p>
                </div>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="text-sm text-amber-600 hover:underline mt-3 inline-block">
                Go to Shop Dashboard →
            </a>
        </div>
        @else
        <!-- Benefits -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition">
                <div class="text-4xl mb-3">👥</div>
                <p class="font-semibold text-gray-800">Reach More Customers</p>
                <p class="text-sm text-gray-500">Connect with customers in Victorias City</p>
            </div>
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition">
                <div class="text-4xl mb-3">📦</div>
                <p class="font-semibold text-gray-800">Easy Management</p>
                <p class="text-sm text-gray-500">Manage products, orders, and branches</p>
            </div>
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition">
                <div class="text-4xl mb-3">💳</div>
                <p class="font-semibold text-gray-800">Secure Payments</p>
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
            <a href="{{ route('livewire.guest.seller-registration') }}"
                class="inline-flex items-center px-10 py-4 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition text-lg font-semibold shadow-md hover:shadow-lg">
                Start Registration →
            </a>
            <p class="text-sm text-gray-400 mt-3">This will take about 5 minutes</p>
        </div>
        @endif
    </div>
</div>