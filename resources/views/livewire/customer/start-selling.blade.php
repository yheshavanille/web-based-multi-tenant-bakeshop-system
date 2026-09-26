<div>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 border border-amber-200 flex items-center justify-center shadow-sm">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Start Selling on
                Web-based Multi-Tenant Bakeshop System</h1>
            <p class="text-gray-500 mt-2">Reach more customers and grow your bakeshop business.</p>
        </div>

        @if($hasPendingApplication)
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg">
            <p class="font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Application Pending
            </p>
            <p class="text-sm">You already have a pending seller application. Please wait for admin approval.</p>
            <a href="{{ route('livewire.customer.dashboard') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:underline mt-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
        @elseif($isAlreadySeller)
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            <p class="font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                You're already a seller!
            </p>
            <p class="text-sm">You can manage your shop from the dashboard.</p>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="inline-flex items-center gap-1 text-sm text-amber-600 hover:underline mt-2">
                Go to Shop Dashboard
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
        @else
        <!-- Benefits -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <p class="font-medium text-gray-800">Reach More Customers</p>
                <p class="text-sm text-gray-500">Connect with customers in Victorias City</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="font-medium text-gray-800">Easy Management</p>
                <p class="text-sm text-gray-500">Manage products, orders, and branches</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                </div>
                <p class="font-medium text-gray-800">Secure Payments</p>
                <p class="text-sm text-gray-500">GCash, PayMaya, and Cash on Pickup</p>
            </div>
        </div>

        <!-- Requirements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                Requirements
            </h2>
            <ul class="space-y-2.5 text-gray-600">
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Must be at least 18 years old
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid Government ID
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid Business Permit from LGU
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid contact number and address
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Bakeshop located in Victorias City
                </li>
            </ul>
        </div>

        <!-- Start Button -->
        <div class="text-center">
            <a href="{{ route('livewire.customer.seller-registration') }}"
                class="inline-flex items-center gap-2 px-8 py-4 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition text-lg font-semibold shadow-md hover:shadow-lg">
                Start Registration
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
            <p class="text-sm text-gray-400 mt-3">This will take about 5 minutes</p>
        </div>
        @endif
    </div>
</div>