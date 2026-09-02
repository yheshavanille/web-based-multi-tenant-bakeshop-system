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
            <span class="text-sm font-semibold text-gray-800">🔔 Notifications</span>
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
                <span class="text-lg">📦</span>
                @elseif($notification->data['type'] === 'order_status_updated')
                <span class="text-lg">🔄</span>
                @elseif($notification->data['type'] === 'low_stock')
                @if(isset($notification->data['is_out_of_stock']) && $notification->data['is_out_of_stock'])
                <span class="text-lg">🚫</span>
                @else
                <span class="text-lg">⚠️</span>
                @endif
                @elseif($notification->data['type'] === 'seller_approved')
                <span class="text-lg">🎉</span>
                @elseif($notification->data['type'] === 'seller_rejected')
                <span class="text-lg">❌</span>
                @elseif($notification->data['type'] === 'new_seller_registration')
                <span class="text-lg">📋</span>
                @else
                <span class="text-lg">🔔</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 {{ $notification->read_at ? '' : 'font-semibold' }}">
                    {{ $notification->data['message'] }}
                </p>
                @if(isset($notification->data['custom_note']))
                <p class="text-xs text-amber-600 mt-0.5 italic">💬 "{{ $notification->data['custom_note'] }}"</p>
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
            <span class="text-3xl block mb-2">📭</span>
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
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity backdrop-blur-sm"
            wire:click="closeModal"></div>

        <!-- ✅ CLEAN MODAL -->
        <div
            class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">

            <!-- Modal Header -->
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

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                @if($selectedNotification->data['type'] === 'new_seller_registration')
                <div class="bg-purple-50 rounded-lg p-3 border border-purple-200">
                    <p class="text-sm font-semibold text-purple-800">📋 New Seller Application!</p>
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
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Close
                    </button>
                </div>
                @endif

                @if($selectedNotification->data['type'] === 'seller_approved')
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <p class="text-sm font-semibold text-green-800">✅ Seller Application Approved!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @if(isset($selectedNotification->data['custom_note']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs text-amber-600 font-medium">💬 Custom Note:</p>
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
                    <p class="text-sm font-semibold text-red-800">❌ Seller Application Rejected</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @if(isset($selectedNotification->data['rejection_reason']))
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-xs text-red-600 font-medium">📝 Rejection Reason:</p>
                    <p class="text-sm text-gray-700">{{ $selectedNotification->data['rejection_reason'] }}</p>
                </div>
                @endif
                @if(isset($selectedNotification->data['custom_note']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs text-amber-600 font-medium">💬 Custom Note:</p>
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
                    <p class="text-sm font-semibold text-blue-800">📦 New Order!</p>
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
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📦 Order Items</h4>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($orderDetails->items as $item)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">
                                    Qty: {{ $item->quantity }}
                                    @if($item->original_price && $item->original_price > $item->price)
                                    <span class="text-red-600 font-medium">₱{{ number_format($item->price, 2) }}</span>
                                    <span class="text-gray-400 line-through ml-1">₱{{
                                        number_format($item->original_price, 2) }}</span>
                                    @else
                                    x ₱{{ number_format($item->price, 2) }}
                                    @endif
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-amber-600">₱{{ number_format($item->price *
                                $item->quantity, 2) }}</p>
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
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📍 Pickup Details</h4>
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
                    <p class="text-sm font-semibold text-blue-800">🔄 Order Status Updated</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order #</p>
                        <p class="text-sm font-medium text-gray-800">{{ $selectedNotification->data['order_number'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Old Status</p>
                        <p class="text-sm font-medium text-yellow-600">{{
                            $selectedNotification->data['old_status_label'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">New Status</p>
                        <p class="text-sm font-medium text-green-600">→ {{
                            $selectedNotification->data['new_status_label'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Customer</p>
                        <p class="text-sm font-medium text-gray-800">{{ $orderDetails?->customer?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="text-sm font-medium text-gray-800">{{ $orderDetails?->shop?->shop_name ?? 'N/A' }}</p>
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
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📦 Order Items</h4>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($orderDetails->items as $item)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">
                                    Qty: {{ $item->quantity }}
                                    @if($item->original_price && $item->original_price > $item->price)
                                    <span class="text-red-600 font-medium">₱{{ number_format($item->price, 2) }}</span>
                                    <span class="text-gray-400 line-through ml-1">₱{{
                                        number_format($item->original_price, 2) }}</span>
                                    @else
                                    x ₱{{ number_format($item->price, 2) }}
                                    @endif
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-amber-600">₱{{ number_format($item->price *
                                $item->quantity, 2) }}</p>
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
                @endif

                <!-- Low Stock -->
                @if($selectedNotification->data['type'] === 'low_stock')
                @if(isset($selectedNotification->data['is_out_of_stock']) &&
                $selectedNotification->data['is_out_of_stock'])
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800">🚫 Out of Stock!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>
                @else
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <p class="text-sm font-semibold text-red-800">⚠️ Low Stock Alert</p>
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
                <a href="{{ route('livewire.employee.inventory') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                    Manage Stock →
                </a>
                @endif

            </div>

            <!-- Modal Footer -->
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