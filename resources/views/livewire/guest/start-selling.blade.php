<div>
    <div class="max-w-3xl mx-auto px-4 pt-16 pb-8 sm:py-8">

        <!-- Header -->
        <div class="text-center mb-10">
            {{-- ✅ Replaced the giant cake emoji with a clean icon badge --}}
            <div class="w-24 h-24 mx-auto mb-5 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 border border-amber-200 flex items-center justify-center shadow-sm">
                <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Start Selling on</h1>
            <h2 class="text-2xl font-bold text-amber-600">Web-based Multi-Tenant Bakeshop System</h2>
            <p class="text-gray-500 mt-3 max-w-md mx-auto">Reach more customers and grow your bakeshop business.</p>
        </div>

        @if(!Auth::check())
        <div class="mb-6 p-6 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl shadow-sm">
            <div class="flex flex-col items-center gap-2 text-center">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Please Login or Register First</p>
                    <p class="text-sm text-gray-600">You need to be logged in to start selling.</p>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-3 mt-4">
                <a href="{{ route('livewire.auth.login') }}?start_selling=true"
                    class="inline-flex items-center gap-2 text-sm bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium shadow-sm hover:shadow">
                    Login Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <a href="{{ route('livewire.auth.register') }}?start_selling=true"
                    class="inline-flex items-center gap-2 text-sm bg-white border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition font-medium">
                    Create Account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
        @elseif($hasPendingApplication)
        <div class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Application Pending</p>
                    <p class="text-sm text-gray-600">You already have a pending seller application. Please wait for admin approval.</p>
                </div>
            </div>
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:underline mt-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
        @elseif($isAlreadySeller)
        <div class="mb-6 p-6 bg-green-50 border border-green-200 rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">You're already a seller!</p>
                    <p class="text-sm text-gray-600">You can manage your shop from the dashboard.</p>
                </div>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:underline mt-3">
                Go to Shop Dashboard
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
        @else
        <!-- Benefits -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="font-semibold text-gray-800">Reach More Customers</p>
                <p class="text-sm text-gray-500">Connect with customers in Victorias City</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="font-semibold text-gray-800">Easy Management</p>
                <p class="text-sm text-gray-500">Manage products, orders, and branches</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <p class="font-semibold text-gray-800">Secure Payments</p>
                <p class="text-sm text-gray-500">GCash, PayMaya, and Cash on Pickup</p>
            </div>
        </div>

        <!-- Requirements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Requirements
            </h2>
            <ul class="space-y-2.5 text-gray-600">
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Must be at least 18 years old
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid Government ID
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid Business Permit from LGU
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid contact number and address
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Bakeshop located in Victorias City
                </li>
            </ul>
        </div>

        <!-- Start Button -->
        <div class="text-center">
            <a href="{{ route('livewire.guest.seller-registration') }}"
                class="inline-flex items-center gap-2 px-10 py-4 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition text-lg font-semibold shadow-md hover:shadow-lg">
                Start Registration
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
            <p class="text-sm text-gray-400 mt-3">This will take about 5 minutes</p>
        </div>
        @endif
    </div>
</div>
