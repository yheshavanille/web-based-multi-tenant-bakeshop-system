<?php

namespace App\Livewire\Components;

use App\Models\Order;
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

    protected $listeners = ['notificationUpdated' => 'loadNotifications'];

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

        // ✅ CUSTOMER VIEW: Only show customer's own order notifications
        if ($isCustomerView && !$isOwnerView && !$isEmployeeView && !$isAdminView) {
            // Customers should only see their own order status updates
            if ($type === 'order_status_updated') {
                // Check if this notification is for the customer
                $orderId = $notification->data['order_id'] ?? null;
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order && $order->customer_id === $user->id) {
                        return true; // This is the customer's own order
                    }
                }
                return false; // Not their order
            }
            return in_array($type, ['seller_approved', 'seller_rejected']);
        }

        // ✅ OWNER VIEW: Show all owner notifications
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

        // ✅ ADMIN VIEW: Show everything
        if ($isAdminView) {
            return true;
        }

        // ✅ Fallback
        if ($user->hasRole('owner') && $isCustomerView) {
            // Check if this is the customer's own order
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

        // ✅ Load order details if notification is order-related
        $this->orderDetails = null;
        if (in_array($this->selectedNotification->data['type'] ?? '', ['new_order', 'order_status_updated'])) {
            $orderId = $this->selectedNotification->data['order_id'] ?? null;
            if ($orderId) {
                $this->orderDetails = Order::with(['items.product', 'customer', 'branch', 'shop'])
                    ->find($orderId);
            }
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedNotification = null;
        $this->orderDetails = null;
    }

    public function render()
    {
        return view('livewire.components.notification-bell');
    }
}
