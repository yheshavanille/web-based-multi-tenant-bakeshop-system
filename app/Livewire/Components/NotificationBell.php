<?php

namespace App\Livewire\Components;

use App\Models\Order;
use App\Models\SellerRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showModal = false;
    public $selectedNotification = null;
    public $orderDetails = null;
    public $sellerRegistration = null;

    // ✅ ADD refreshNotifications listener
    protected $listeners = [
        'notificationUpdated' => 'loadNotifications',
        'refreshNotifications' => 'loadNotifications',
    ];

    public function mount()
    {
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
        $currentRoute = Route::currentRouteName() ?? '';

        $isCustomerView = str_starts_with($currentRoute, 'livewire.customer.')
            || str_starts_with($currentRoute, 'customer.')
            || request()->is('customer/*');

        $isOwnerView = str_starts_with($currentRoute, 'livewire.owner.')
            || str_starts_with($currentRoute, 'owner.')
            || request()->is('owner/*');

        $isEmployeeView = str_starts_with($currentRoute, 'livewire.employee.')
            || str_starts_with($currentRoute, 'employee.')
            || request()->is('employee/*');

        $isAdminView = str_starts_with($currentRoute, 'livewire.admin.')
            || str_starts_with($currentRoute, 'admin.')
            || request()->is('admin/*');

        // ✅ CUSTOMER VIEW
        if ($isCustomerView && !$isOwnerView && !$isEmployeeView && !$isAdminView) {
            if ($type === 'order_status_updated') {
                $orderId = $notification->data['order_id'] ?? null;
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order && $order->customer_id === $user->id) {
                        return true;
                    }
                }
                return false;
            }
            return in_array($type, ['seller_approved', 'seller_rejected']);
        }

        // ✅ OWNER VIEW
        if ($isOwnerView) {
            return in_array($type, ['new_order', 'order_status_updated', 'seller_approved', 'seller_rejected']);
        }

        // ✅ EMPLOYEE VIEW
        if ($isEmployeeView) {
            $employee = $user->employee;
            if (!$employee) return false;

            if ($employee->role === 'order_manager') {
                return $type === 'new_order' || $type === 'order_status_updated';
            }

            if ($employee->role === 'inventory_manager') {
                return $type === 'low_stock';
            }
        }

        // ✅ ADMIN VIEW
        if ($isAdminView) {
            return true;
        }

        return true;
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
