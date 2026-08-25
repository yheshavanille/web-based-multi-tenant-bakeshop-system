<div class="relative" x-data="{ open: false }">
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

    <!-- Dropdown -->
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
                <span class="text-lg">⚠️</span>
                @elseif($notification->data['type'] === 'seller_approved')
                <span class="text-lg">🎉</span>
                @elseif($notification->data['type'] === 'seller_rejected')
                <span class="text-lg">❌</span>
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

    <!-- Notification Details Modal -->
    @if($showModal && $selectedNotification)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity backdrop-blur-sm"
            wire:click="closeModal"></div>

        <div
            class="relative z-10 w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($selectedNotification->data['type'] === 'seller_approved')
                        <span class="text-3xl">🎉</span>
                        @elseif($selectedNotification->data['type'] === 'seller_rejected')
                        <span class="text-3xl">❌</span>
                        @elseif($selectedNotification->data['type'] === 'new_order')
                        <span class="text-3xl">📦</span>
                        @elseif($selectedNotification->data['type'] === 'order_status_updated')
                        <span class="text-3xl">🔄</span>
                        @elseif($selectedNotification->data['type'] === 'low_stock')
                        <span class="text-3xl">⚠️</span>
                        @else
                        <span class="text-3xl">🔔</span>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Notification Details</h3>
                            <p class="text-sm text-gray-500">{{ $selectedNotification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <!-- SELLER APPROVED -->
                @if($selectedNotification->data['type'] === 'seller_approved')
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <p class="text-lg font-semibold text-green-800">✅ Seller Application Approved!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                @if(isset($selectedNotification->data['requirements']) &&
                count($selectedNotification->data['requirements']) > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📋 Requirements Met:</h4>
                    <div class="space-y-1">
                        @foreach($selectedNotification->data['requirements'] as $req)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-green-500">✅</span>
                            <span class="{{ $req['met'] ? 'text-gray-800' : 'text-gray-400 line-through' }}">{{
                                $req['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($selectedNotification->data['custom_note']))
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                    <p class="text-xs text-amber-600 font-medium">💬 Custom Note:</p>
                    <p class="text-sm text-gray-700 italic">"{{ $selectedNotification->data['custom_note'] }}"</p>
                </div>
                @endif

                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    Go to Shop Dashboard →
                </a>
                @endif

                <!-- SELLER REJECTED -->
                @if($selectedNotification->data['type'] === 'seller_rejected')
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <p class="text-lg font-semibold text-red-800">❌ Seller Application Rejected</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                @if(isset($selectedNotification->data['requirements']) &&
                count($selectedNotification->data['requirements']) > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📋 Requirements Check:</h4>
                    <div class="space-y-1">
                        @foreach($selectedNotification->data['requirements'] as $req)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="{{ $req['met'] ? 'text-green-500' : 'text-red-500' }}">{{ $req['met'] ? '✅' :
                                '❌' }}</span>
                            <span class="{{ $req['met'] ? 'text-gray-800' : 'text-red-600' }}">{{ $req['label']
                                }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

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
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    Reapply →
                </a>
                @endif

                <!-- NEW ORDER -->
                @if($selectedNotification->data['type'] === 'new_order')
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <p class="text-lg font-semibold text-blue-800">📦 New Order!</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order #</p>
                        <p class="font-medium text-gray-800">{{ $selectedNotification->data['order_number'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Total Amount</p>
                        <p class="font-medium text-amber-600">₱{{
                            number_format($selectedNotification->data['total_amount'] ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Customer</p>
                        <p class="font-medium text-gray-800">{{ $selectedNotification->data['customer_name'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Shop</p>
                        <p class="font-medium text-gray-800">{{ $selectedNotification->data['shop_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="font-medium text-gray-800">
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

                <!-- Order Items -->
                @if(isset($orderDetails) && $orderDetails && $orderDetails->items->count() > 0)
                <div class="border-t border-gray-200 pt-3 mt-2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📦 Order Items</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($orderDetails->items as $item)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }} x ₱{{
                                    number_format($item->price, 2) }}</p>
                            </div>
                            <p class="text-sm font-semibold text-amber-600">₱{{ number_format($item->price *
                                $item->quantity, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-200">
                        <span class="text-sm font-bold text-gray-700">Total</span>
                        <span class="text-lg font-bold text-amber-600">₱{{ number_format($orderDetails->total_amount, 2)
                            }}</span>
                    </div>
                </div>
                @endif

                <!-- Close Button -->
                <div class="flex gap-3 mt-4 pt-2">
                    <button wire:click="closeModal"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Close
                    </button>
                </div>
                @endif

                <!-- ORDER STATUS UPDATED -->
                @if($selectedNotification->data['type'] === 'order_status_updated')
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <p class="text-lg font-semibold text-blue-800">🔄 Order Status Updated</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Order #</p>
                        <p class="font-medium text-gray-800">{{ $selectedNotification->data['order_number'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Old Status</p>
                        <p class="font-medium text-yellow-600">{{ $selectedNotification->data['old_status_label'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">New Status</p>
                        <p class="font-medium text-green-600">→ {{ $selectedNotification->data['new_status_label'] ??
                            'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Payment Method</p>
                        <p class="font-medium text-gray-800">
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

                <!-- Order Items -->
                @if(isset($orderDetails) && $orderDetails && $orderDetails->items->count() > 0)
                <div class="border-t border-gray-200 pt-3 mt-2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📦 Order Items</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($orderDetails->items as $item)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }} x ₱{{
                                    number_format($item->price, 2) }}</p>
                            </div>
                            <p class="text-sm font-semibold text-amber-600">₱{{ number_format($item->price *
                                $item->quantity, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-200">
                        <span class="text-sm font-bold text-gray-700">Total</span>
                        <span class="text-lg font-bold text-amber-600">₱{{ number_format($orderDetails->total_amount, 2)
                            }}</span>
                    </div>
                </div>
                @endif

                <!-- Close Button -->
                <div class="flex gap-3 mt-4 pt-2">
                    <button wire:click="closeModal"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Close
                    </button>
                </div>
                @endif

                <!-- LOW STOCK -->
                @if($selectedNotification->data['type'] === 'low_stock')
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <p class="text-lg font-semibold text-red-800">⚠️ Low Stock Alert</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $selectedNotification->data['message'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Product</p>
                        <p class="font-medium text-gray-800">{{ $selectedNotification->data['product_name'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="font-medium text-gray-800">{{ $selectedNotification->data['branch_name'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Current Stock</p>
                        <p class="font-medium text-red-600">{{ $selectedNotification->data['stock'] ?? 0 }}</p>
                    </div>
                </div>

                <a href="{{ route('livewire.employee.inventory') }}"
                    class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
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