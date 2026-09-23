<?php

namespace App\Livewire\Components;

use App\Models\Order;
use App\Models\SellerRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showModal = false;
    public $selectedNotification = null;
    public $orderDetails = null;
    public $sellerRegistration = null;

    public $context = 'customer';

    protected $listeners = [
        'notificationUpdated' => 'loadNotifications',
        'refreshNotifications' => 'loadNotifications',
    ];

    public function mount($context = 'customer')
    {
        $this->context = $context;
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (!Auth::check()) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        $user = Auth::user();
        $allNotifications = $user->notifications()->limit(10)->get();

        $this->notifications = $allNotifications->filter(function ($notification) use ($user) {
            return $this->shouldShowNotification($user, $notification);
        });

        $this->unreadCount = $this->notifications->filter(function ($notification) {
            return is_null($notification->read_at);
        })->count();
    }

    private function shouldShowNotification($user, $notification)
    {
        // ✅ SUPER ADMIN - SHOW EVERYTHING
        if ($user->hasRole('super_admin')) {
            return true;
        }

        $type = $notification->data['type'] ?? '';

        $isCustomerView = $this->context === 'customer';
        $isOwnerView    = $this->context === 'owner';
        $isEmployeeView = $this->context === 'employee';
        $isAdminView    = $this->context === 'admin';

        // ✅ CUSTOMER VIEW
        if ($isCustomerView) {
            // Handle order-related checks first
            if ($type === 'order_status_updated') {
                $orderId = $notification->data['order_id'] ?? null;
                if ($orderId) {
                    $order = Order::with('shop')->find($orderId);
                    if ($order) {
                        if ($order->shop && $order->shop->user_id === $user->id) {
                            return false;
                        }
                        if ($order->customer_id === $user->id) {
                            return true;
                        }
                    }
                }
                return false;
            }

            if ($type === 'new_order') {
                return false;
            }

            // ✅ Review moderation: only show if the customer is the recipient
            //    (i.e., the reviewer whose review was moderated)
            if (in_array($type, ['review_moderation_removed', 'review_moderation_banned'])) {
                return isset($notification->data['is_customer'])
                    && $notification->data['is_customer'] === true;
            }

            // ✅ User lifecycle: only show if the notification is about THIS user
            if (in_array($type, ['user_suspended', 'user_archived', 'user_restored'])) {
                return ($notification->data['user_id'] ?? null) === $user->id;
            }

            // Other customer-safe notifications
            return in_array($type, [
                'seller_approved',
                'seller_rejected',
                'shop_deleted_by_admin',
                'shop_restored_by_admin',
            ]);
        }

        // ✅ OWNER VIEW
        if ($isOwnerView) {
            $ownedShopId = $user->shop?->id;

            $orderId = $notification->data['order_id'] ?? null;
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $ownedShopId && $order->shop_id === $ownedShopId) {
                    return true;
                }
                return false;
            }

            // ✅ Owner gets review moderation notifications (they flagged it)
            //    but NOT customer-specific review bans/reviews they didn't write
            if ($type === 'review_moderation_kept') {
                return true; // only sent to owners anyway
            }

            if (in_array($type, ['review_moderation_removed', 'review_moderation_banned'])) {
                // Show only if this owner was the flagger (not the reviewer)
                return !isset($notification->data['is_customer'])
                    || $notification->data['is_customer'] === false;
            }

            return in_array($type, [
                'new_order',
                'order_status_updated',
                'seller_approved',
                'seller_rejected',
                'shop_restored_by_admin',
                'low_stock',
                'stock_review_needed',
                'user_suspended',
                'user_archived',
                'user_restored',
            ]);
        }

        // ✅ EMPLOYEE VIEW
        if ($isEmployeeView) {
            $employee = $user->employee;
            if (!$employee) return false;

            $orderId = $notification->data['order_id'] ?? null;
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->branch_id === $employee->branch_id) {
                    if ($employee->role === 'order_manager') {
                        return in_array($type, ['new_order', 'order_status_updated']);
                    }
                    if ($employee->role === 'inventory_manager') {
                        return in_array($type, ['low_stock', 'stock_review_needed']);
                    }
                }
                return false;
            }

            if ($employee->role === 'inventory_manager') {
                return in_array($type, ['low_stock', 'stock_review_needed']);
            }

            return false;
        }

        // ✅ ADMIN VIEW
        if ($isAdminView) {
            return true;
        }

        return false;
    }

    public function loadUnreadCount()
    {
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            $visibleIds = $this->notifications->pluck('id')->toArray();
            Auth::user()->notifications()
                ->whereIn('id', $visibleIds)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        }
    }

    public function openNotificationModal($notificationId)
    {
        $this->selectedNotification = Auth::user()->notifications()->find($notificationId);

        if ($this->selectedNotification && !$this->selectedNotification->read_at) {
            $this->selectedNotification->markAsRead();
            $this->loadNotifications();
        }

        $this->orderDetails = null;
        $this->sellerRegistration = null;

        $type = $this->selectedNotification->data['type'] ?? '';

        if (in_array($type, ['new_order', 'order_status_updated'])) {
            $orderId = $this->selectedNotification->data['order_id'] ?? null;
            if ($orderId) {
                $this->orderDetails = Order::with(['items.product', 'customer', 'branch', 'shop'])
                    ->find($orderId);
            }
        }

        if (in_array($type, ['new_seller_registration', 'seller_approved', 'seller_rejected'])) {
            $registrationId = $this->selectedNotification->data['registration_id'] ?? null;
            if ($registrationId) {
                $this->sellerRegistration = SellerRegistration::with(['user'])
                    ->find($registrationId);
            }
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedNotification = null;
        $this->orderDetails = null;
        $this->sellerRegistration = null;
    }

    public function render()
    {
        return view('livewire.components.notification-bell');
    }
}
