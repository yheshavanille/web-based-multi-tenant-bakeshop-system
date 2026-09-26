<div class="relative" x-data="{ open: false }" wire:poll.10s="loadNotifications">
    <button @click="open = !open" @click.away="open = false"
        class="relative p-2 text-gray-600 hover:text-amber-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        @if($unreadCount > 0)
        <span
            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
            {{ $unreadCount }}
        </span>
        @endif
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 max-h-96 overflow-y-auto">
        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
            <span class="text-sm font-semibold text-gray-800 inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Notifications
            </span>
            @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-xs text-amber-600 hover:text-amber-700 font-medium">
                Mark all as read
            </button>
            @endif
        </div>

        @if(Auth::check())
        @if($notifications->count() > 0)
        @foreach($notifications as $notification)
        <button wire:click="openNotificationModal('{{ $notification->id }}')"
            class="w-full text-left flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 {{ $notification->read_at ? 'opacity-75' : 'bg-amber-50' }}">
            <div class="flex-shrink-0 mt-0.5">
                @if($notification->data['type'] === 'new_order')
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                @elseif($notification->data['type'] === 'order_status_updated')
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                @elseif($notification->data['type'] === 'low_stock')
                @if(isset($notification->data['is_out_of_stock']) && $notification->data['is_out_of_stock'])
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                @else
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                @endif
                @elseif($notification->data['type'] === 'stock_review_needed')
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                @elseif($notification->data['type'] === 'seller_approved')
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($notification->data['type'] === 'seller_rejected')
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($notification->data['type'] === 'new_seller_registration')
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                @elseif($notification->data['type'] === 'shop_deleted_by_owner')
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                @elseif($notification->data['type'] === 'shop_deleted_by_admin')
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($notification->data['type'] === 'shop_restored_by_admin')
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($notification->data['type'] === 'review_moderation_kept')
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($notification->data['type'] === 'review_moderation_removed')
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($notification->data['type'] === 'review_moderation_banned')
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                @elseif($notification->data['type'] === 'user_suspended')
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                @elseif($notification->data['type'] === 'user_archived')
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                @elseif($notification->data['type'] === 'user_restored')
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @else
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 {{ $notification->read_at ? '' : 'font-semibold' }}">
                    {{ $notification->data['message'] }}
                </p>
                @if(isset($notification->data['custom_note']))
                <p class="text-xs text-amber-600 mt-0.5 italic inline-flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    "{{ $notification->data['custom_note'] }}"
                </p>
                @endif
                <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notification->read_at)
            <div class="w-2 h-2 bg-amber-500 rounded-full flex-shrink-0 mt-1.5"></div>
            @endif
        </button>
        @endforeach
        @else
        <div class="px-4 py-6 text-center text-gray-500 text-sm">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            No notifications yet
        </div>
        @endif
        @else
        <div class="px-4 py-6 text-center text-gray-500 text-sm">
            Please login to see notifications
        </div>
        @endif
    </div>

    @if($showModal && $selectedNotification)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal"></div>

        <div
            class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">

            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Notification Details</h3>
                        <p class="text-xs text-gray-500">{{ $selectedNotification->created_at->diffForHumans() }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                @if($selectedNotification->data['type'] === 'new_seller_registration')
                <div class="bg-purple-50 rounded-lg p-3 border border-purple-200">
                    <p class="text-sm font-semibold text-purple-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        New Seller Application!
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Applicant</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['applicant_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Business Name</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['business_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Business Address</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['business_address']
                            ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Contact Number</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['contact_number'] ??
                            'N/A' }}</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-2">
                    <a href="{{ route('livewire.admin.pending-sellers') }}"
                        class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium text-center">
                        Review Application →
                    </a>
                </div>
                @endif

                @if($selectedNotification->data['type'] === 'seller_approved')
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <p class="text-sm font-semibold text-green-800">Seller Application Approved!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @if(isset($selectedNotification->data['custom_note']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs text-amber-600 font-medium inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Custom Note:
                    </p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['custom_note'] }}"</p>
                </div>
                @endif
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Go to Shop Dashboard →
                </a>
                @endif

                @if($selectedNotification->data['type'] === 'seller_rejected')
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800">Seller Application Rejected</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @if(isset($selectedNotification->data['rejection_reason']))
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-xs text-red-600 font-medium">Rejection Reason:</p>
                    <p class="text-sm text-gray-700">{{ $selectedNotification->data['rejection_reason'] }}</p>
                </div>
                @endif
                @if(isset($selectedNotification->data['custom_note']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs text-amber-600 font-medium">Custom Note:</p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['custom_note'] }}"</p>
                </div>
                @endif
                <a href="{{ route('livewire.customer.start-selling') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Reapply →
                </a>
                @endif

                @if($selectedNotification->data['type'] === 'new_order')
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm font-semibold text-blue-800">New Order!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order #</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['order_number'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Customer</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['customer_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="text-sm font-medium text-gray-800">{{ $orderDetails?->branch?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="text-sm font-medium text-gray-800">
                            @php
                            $paymentMethod = $selectedNotification->data['payment_method'] ?? 'N/A';
                            $paymentMethodLabel = match($paymentMethod) {
                            'gcash' => 'GCash',
                            'paymaya' => 'PayMaya',
                            'paymongo' => 'PayMongo',
                            'pickup_payment' => 'Cash on Pickup',
                            default => ucfirst(str_replace('_', ' ', $paymentMethod)),
                            };
                            @endphp
                            {{ $paymentMethodLabel }}
                        </p>
                    </div>
                </div>

                @if(isset($orderDetails) && $orderDetails && $orderDetails->items->count() > 0)
                <div class="border-t border-gray-200 pt-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Order Items
                    </h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($orderDetails->items as $item)
                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800">{{ $item->product->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">
                                        Qty: {{ $item->quantity }}
                                        @if($item->original_price && $item->original_price > $item->price)
                                        <span class="text-red-600 font-medium">₱{{ number_format($item->price, 2)
                                            }}</span>
                                        <span class="text-gray-400 line-through ml-1">₱{{
                                            number_format($item->original_price, 2) }}</span>
                                        @else
                                        x ₱{{ number_format($item->price, 2) }}
                                        @endif
                                    </p>

                                    @if(!empty($item->notes))
                                    <div class="mt-1.5">
                                        <div
                                            class="inline-flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-lg px-2.5 py-1.5 max-w-full">
                                            <span class="text-xs font-medium text-gray-700 flex-shrink-0">Order
                                                Note:</span>
                                            <p class="text-xs text-gray-700 italic leading-snug break-words">{{
                                                $item->notes }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-amber-600 flex-shrink-0">₱{{
                                    number_format($item->price *
                                    $item->quantity, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($orderDetails))
                <div class="border-t border-gray-200 pt-3">
                    <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700">₱{{ number_format($orderDetails->subtotal ??
                                $orderDetails->total_amount, 2) }}</span>
                        </div>
                        @if($orderDetails->tax_amount)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">VAT (12%)</span>
                            <span class="text-gray-700">₱{{ number_format($orderDetails->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-base font-bold pt-1 border-t border-gray-200">
                            <span class="text-gray-800">Total</span>
                            <span class="text-amber-600">₱{{ number_format($orderDetails->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($orderDetails) && $orderDetails->pickup_time)
                <div class="border-t border-gray-200 pt-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pickup Details
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Branch:</span> {{ $orderDetails->branch?->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Pickup Time:</span> {{
                            \Carbon\Carbon::parse($orderDetails->pickup_time)->format('M d, Y h:i A') }}
                        </p>
                        @if($orderDetails->notes)
                        <p class="text-sm text-gray-700 mt-1">
                            <span class="font-medium">Notes:</span> {{ $orderDetails->notes }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
                @endif

                @if($selectedNotification->data['type'] === 'order_status_updated')
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm font-semibold text-blue-800">Order Status Updated</p>
                    <p class="text-sm text-gray-700 mt-1">Order #{{ $selectedNotification->data['order_number'] ?? 'N/A'
                        }}</p>
                </div>

                @if(isset($selectedNotification->data['product_name']) && $selectedNotification->data['product_name'])
                <div class="bg-amber-50 rounded-lg p-4 border-2 border-amber-300">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Item Status Changed
                    </p>
                    <p class="text-base font-bold text-gray-800 mb-2">{{ $selectedNotification->data['product_name'] }}
                    </p>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-yellow-600 font-medium">{{ $selectedNotification->data['old_status_label'] ??
                            'N/A' }}</span>
                        <span class="text-gray-400">→</span>
                        <span class="text-green-600 font-medium">{{ $selectedNotification->data['new_status_label'] ??
                            'N/A' }}</span>
                    </div>
                </div>
                @else
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Old Status</p>
                        <p class="text-sm font-medium text-yellow-600">{{
                            $selectedNotification->data['old_status_label'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">New Status</p>
                        <p class="text-sm font-medium text-green-600">{{ $selectedNotification->data['new_status_label']
                            ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order #</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['order_number'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Customer</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['customer_name'] ??
                            ($orderDetails?->customer?->name ?? 'N/A') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ??
                            ($orderDetails?->shop?->shop_name ?? 'N/A') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="text-sm font-medium text-gray-800">
                            @php
                            $paymentMethod = $selectedNotification->data['payment_method'] ?? 'N/A';
                            $paymentMethodLabel = match($paymentMethod) {
                            'gcash' => 'GCash',
                            'paymaya' => 'PayMaya',
                            'paymongo' => 'PayMongo',
                            'pickup_payment' => 'Cash on Pickup',
                            default => ucfirst(str_replace('_', ' ', $paymentMethod)),
                            };
                            @endphp
                            {{ $paymentMethodLabel }}
                        </p>
                    </div>
                </div>

                @if(isset($orderDetails) && $orderDetails && $orderDetails->items->count() > 0)
                @php
                $chargedItems = $orderDetails->items->where('status', 'completed');
                $notChargedItems = $orderDetails->items->whereIn('status', ['cancelled', 'no_show']);
                $outstandingItems = $orderDetails->items->whereIn('status', ['pending', 'preparing',
                'ready_for_pickup']);

                $chargedSubtotal = $chargedItems->sum(fn($i) => $i->price * $i->quantity);
                $notChargedSubtotal = $notChargedItems->sum(fn($i) => $i->price * $i->quantity);
                $outstandingSubtotal = $outstandingItems->sum(fn($i) => $i->price * $i->quantity);

                $chargedVat = $chargedSubtotal * 0.12;
                $notChargedVat = $notChargedSubtotal * 0.12;
                $outstandingVat = $outstandingSubtotal * 0.12;

                $chargedTotal = $chargedSubtotal + $chargedVat;
                $notChargedTotal = $notChargedSubtotal + $notChargedVat;
                $outstandingTotal = $outstandingSubtotal + $outstandingVat;

                $originalSubtotal = $orderDetails->subtotal ?? $orderDetails->total_amount;
                $originalVat = $orderDetails->tax_amount ?? ($originalSubtotal * 0.12);
                $originalTotal = $orderDetails->total_amount;
                @endphp

                <div class="border-t border-gray-200 pt-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Order Summary</h4>

                    <div class="bg-gray-50 rounded-lg p-3 mb-3 border border-gray-200">
                        <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Original Order
                        </p>
                        <div class="space-y-1 mb-2">
                            @foreach($orderDetails->items as $item)
                            @php
                            $isUpdatedItem = isset($selectedNotification->data['item_id'])
                            && $selectedNotification->data['item_id'] == $item->id;
                            @endphp
                            <div class="flex justify-between items-center text-sm gap-3 rounded px-3 py-2
                                {{ $isUpdatedItem ? 'bg-amber-100 border border-amber-300' : '' }}">
                                <span class="text-gray-700 truncate">• {{ $item->product->name ?? 'N/A' }} ({{
                                    $item->quantity }}x)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($item->price *
                                    $item->quantity, 2) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-1 pt-2 border-t border-gray-300">
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($originalSubtotal, 2)
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">VAT (12%)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($originalVat, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold pt-1 border-t border-gray-300 gap-2">
                                <span class="text-gray-800">Original Total</span>
                                <span class="text-gray-800 flex-shrink-0">₱{{ number_format($originalTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-3 mb-3 border border-green-200">
                        <p class="text-xs font-bold text-green-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Charged to Customer
                        </p>
                        @if($chargedItems->count() > 0)
                        <div class="space-y-1 mb-2">
                            @foreach($chargedItems as $item)
                            <div class="flex justify-between items-center text-sm gap-2">
                                <span class="text-gray-700 truncate">• {{ $item->product->name ?? 'N/A' }} ({{
                                    $item->quantity }}x)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($item->price *
                                    $item->quantity, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="space-y-1 pt-2 border-t border-green-200">
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">Completed Items</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($chargedSubtotal, 2)
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">VAT (12%)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($chargedVat, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold pt-1 border-t border-green-200 gap-2">
                                <span class="text-green-800">Amount Charged</span>
                                <span class="text-green-800 flex-shrink-0">₱{{ number_format($chargedTotal, 2) }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-500 italic">No completed items yet.</p>
                        @endif
                    </div>

                    <div class="bg-red-50 rounded-lg p-3 mb-3 border border-red-200">
                        <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Not Charged
                        </p>
                        @if($notChargedItems->count() > 0)
                        <div class="space-y-1 mb-2">
                            @foreach($notChargedItems as $item)
                            <div class="flex justify-between items-center text-sm gap-2">
                                <span class="text-gray-700 truncate">• {{ $item->product->name ?? 'N/A' }} ({{
                                    $item->quantity }}x)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($item->price *
                                    $item->quantity, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="space-y-1 pt-2 border-t border-red-200">
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">Cancelled / No Show</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($notChargedSubtotal, 2)
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">VAT (12%)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($notChargedVat, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold pt-1 border-t border-red-200 gap-2">
                                <span class="text-red-800">Amount Not Charged</span>
                                <span class="text-red-800 flex-shrink-0">₱{{ number_format($notChargedTotal, 2)
                                    }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-500 italic">No cancelled or no-show items.</p>
                        @endif
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                        <p class="text-xs font-bold text-yellow-700 uppercase tracking-wider mb-2 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Outstanding
                        </p>
                        @if($outstandingItems->count() > 0)
                        <div class="space-y-1 mb-2">
                            @foreach($outstandingItems as $item)
                            <div class="flex justify-between items-center text-sm gap-2">
                                <span class="text-gray-700 truncate">• {{ $item->product->name ?? 'N/A' }} ({{
                                    $item->quantity }}x)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($item->price *
                                    $item->quantity, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="space-y-1 pt-2 border-t border-yellow-200">
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">Pending + Preparing</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($outstandingSubtotal, 2)
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm gap-2">
                                <span class="text-gray-500">VAT (12%)</span>
                                <span class="text-gray-700 flex-shrink-0">₱{{ number_format($outstandingVat, 2)
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold pt-1 border-t border-yellow-200 gap-2">
                                <span class="text-yellow-800">Amount Outstanding</span>
                                <span class="text-yellow-800 flex-shrink-0">₱{{ number_format($outstandingTotal, 2)
                                    }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-500 italic">No pending or preparing items.</p>
                        @endif
                    </div>
                </div>
                @endif
                @endif

                @if($selectedNotification->data['type'] === 'shop_deleted_by_owner')
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Shop Deleted by Owner
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop Name</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Owner</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['owner_name'] ??
                            'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $selectedNotification->data['owner_email'] ?? '' }}</p>
                    </div>
                </div>

                @if(!empty($selectedNotification->data['reason']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Reason from Owner</p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['reason'] }}"</p>
                </div>
                @endif

                <a href="{{ route('livewire.admin.pages.shops.view-shops') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Go to Shops →
                </a>
                @endif

                @if($selectedNotification->data['type'] === 'shop_deleted_by_admin')
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Shop Deleted
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Shop Name</p>
                    <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ?? 'N/A' }}
                    </p>
                </div>

                @if(!empty($selectedNotification->data['reason']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Reason from Super Admin
                    </p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['reason'] }}"</p>
                </div>
                @endif

                <p class="text-xs text-gray-500">
                    You can apply to become a seller again if you want to reopen your shop.
                </p>

                <a href="{{ route('livewire.customer.start-selling') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Apply Again →
                </a>
                @endif

                @if($selectedNotification->data['type'] === 'shop_restored_by_admin')
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <p class="text-sm font-semibold text-green-800">Shop Restored!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Shop Name</p>
                    <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ?? 'N/A' }}
                    </p>
                </div>

                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Go to Shop Dashboard →
                </a>
                @endif

                @if($selectedNotification->data['type'] === 'low_stock')
                @if(isset($selectedNotification->data['is_out_of_stock']) &&
                $selectedNotification->data['is_out_of_stock'])
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        Out of Stock!
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @else
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Low Stock Alert
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @endif
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Product</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['product_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['branch_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Current Stock</p>
                        <p
                            class="text-sm font-medium {{ isset($selectedNotification->data['is_out_of_stock']) && $selectedNotification->data['is_out_of_stock'] ? 'text-red-600' : 'text-yellow-600' }}">
                            {{ $selectedNotification->data['stock'] ?? 0 }}
                        </p>
                    </div>
                </div>
                @if($context === 'employee')
                <a href="{{ route('livewire.employee.inventory') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Manage Stock →
                </a>
                @else
                <p class="text-xs text-gray-500 italic">
                    The inventory manager has been notified about this stock alert.
                </p>
                @endif
                @endif

                @if($selectedNotification->data['type'] === 'stock_review_needed')
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-sm font-semibold text-amber-800">Stock Review Needed</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Order #</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['order_number'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Product</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['product_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['branch_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Quantity</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['quantity'] ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Reason</p>
                        <p class="text-sm font-medium text-gray-800 capitalize">
                            {{ str_replace('_', ' ', $selectedNotification->data['reason_label'] ?? 'N/A') }}
                        </p>
                    </div>
                </div>

                @if($context === 'employee')
                <a href="{{ route('livewire.employee.inventory') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Check Inventory →
                </a>
                @else
                <p class="text-xs text-gray-500 italic">
                    The inventory manager has been notified to review this product's stock.
                </p>
                @endif
                @endif

                {{-- REVIEW MODERATION KEPT --}}
                @if($selectedNotification->data['type'] === 'review_moderation_kept')
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <p class="text-sm font-semibold text-green-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Flag Reviewed — Review Kept
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Reviewer</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['customer_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Rating</p>
                        <p class="inline-flex items-center gap-0.5 text-amber-500 text-sm">
                            @for($i = 0; $i < ($selectedNotification->data['rating'] ?? 0); $i++)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                            <span class="text-gray-500 ml-1">({{ $selectedNotification->data['rating'] ?? 0 }}/5)</span>
                        </p>
                    </div>
                </div>

                @if(!empty($selectedNotification->data['moderator_notes']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Note from Super Admin
                    </p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['moderator_notes'] }}"</p>
                </div>
                @endif

                <a href="{{ route('livewire.owner.reviews-history') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    View Reviews History →
                </a>
                @endif

                {{-- REVIEW MODERATION REMOVED --}}
                @if($selectedNotification->data['type'] === 'review_moderation_removed')
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800">Flag Reviewed — Review Removed</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if(isset($selectedNotification->data['is_customer']) &&
                    !$selectedNotification->data['is_customer'])
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Reviewer</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['customer_name'] ??
                            'N/A' }}</p>
                    </div>
                    @endif
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Rating</p>
                        <p class="inline-flex items-center gap-0.5 text-amber-500 text-sm">
                            @for($i = 0; $i < ($selectedNotification->data['rating'] ?? 0); $i++)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                            <span class="text-gray-500 ml-1">({{ $selectedNotification->data['rating'] ?? 0 }}/5)</span>
                        </p>
                    </div>
                </div>

                @if(!empty($selectedNotification->data['moderator_notes']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Reason from Super Admin
                    </p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['moderator_notes'] }}"</p>
                </div>
                @endif

                @if(isset($selectedNotification->data['is_customer']) && $selectedNotification->data['is_customer'])
                <a href="{{ route('livewire.customer.orders') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    View My Orders →
                </a>
                @else
                <a href="{{ route('livewire.owner.reviews-history') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    View Reviews History →
                </a>
                @endif
                @endif

                {{-- REVIEW MODERATION BANNED --}}
                @if($selectedNotification->data['type'] === 'review_moderation_banned')
                <div class="bg-red-50 rounded-lg p-3 border-2 border-red-300">
                    <p class="text-sm font-semibold text-red-800">Review Removed & Reviewer Banned</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if(isset($selectedNotification->data['is_customer']) &&
                    !$selectedNotification->data['is_customer'])
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Reviewer (now banned)</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['customer_name'] ??
                            'N/A' }}</p>
                    </div>
                    @endif
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ??
                            'N/A' }}</p>
                    </div>
                </div>

                @if(!empty($selectedNotification->data['moderator_notes']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Reason from Super Admin
                    </p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['moderator_notes'] }}"</p>
                </div>
                @endif

                @if(isset($selectedNotification->data['is_customer']) && $selectedNotification->data['is_customer'])
                <p class="text-xs text-gray-500 italic">
                    If you believe this was a mistake, please contact support.
                </p>
                @else
                <p class="text-xs text-gray-500 italic">
                    Thank you for helping keep the platform trustworthy.
                </p>
                @endif
                @endif

                {{-- USER SUSPENDED --}}
                @if($selectedNotification->data['type'] === 'user_suspended')
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800">Account Suspended</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Account</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['user_name'] ??
                            'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $selectedNotification->data['user_email'] ?? '' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Suspended By</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['by'] ?? 'Super
                            Admin' }}</p>
                    </div>
                </div>

                @if(!empty($selectedNotification->data['reason']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Reason for Suspension</p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['reason'] }}"</p>
                </div>
                @endif

                <p class="text-xs text-gray-500 italic">
                    If you believe this was a mistake, please contact support.
                </p>
                @endif

                {{-- USER ARCHIVED --}}
                @if($selectedNotification->data['type'] === 'user_archived')
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800">Account Archived</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Account</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['user_name'] ??
                            'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $selectedNotification->data['user_email'] ?? '' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Archived By</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['by'] ?? 'Super
                            Admin' }}</p>
                    </div>
                </div>

                @if(!empty($selectedNotification->data['reason']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Reason for Archiving
                    </p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['reason'] }}"</p>
                </div>
                @endif

                <p class="text-xs text-gray-500 italic">
                    If you believe this was a mistake, please contact support.
                </p>
                @endif

                {{-- USER RESTORED --}}
                @if($selectedNotification->data['type'] === 'user_restored')
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <p class="text-sm font-semibold text-green-800 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Account Restored
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Account</p>
                    <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['user_name'] ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $selectedNotification->data['user_email'] ?? '' }}</p>
                </div>

                <a href="{{ route('livewire.auth.login') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Log In →
                </a>
                @endif

            </div>

            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
