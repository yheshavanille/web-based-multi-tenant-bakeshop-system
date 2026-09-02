<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $oldStatusLabel = $this->getStatusLabel($this->oldStatus);
        $newStatusLabel = $this->getStatusLabel($this->newStatus);

        $url = $this->getNotificationUrl($notifiable);

        $paymentMethod = $this->order->payment_method ?? 'N/A';
        $paymentMethodLabel = $this->getPaymentMethodLabel($paymentMethod);

        return [
            'type' => 'order_status_updated',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->customer->name ?? 'Customer',
            'shop_name' => $this->order->shop->shop_name ?? 'Shop',
            'total_amount' => $this->order->total_amount,
            'branch_id' => $this->order->branch_id,
            'payment_method' => $paymentMethod,
            'payment_method_label' => $paymentMethodLabel,
            'payment_status' => $this->order->payment_status,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_label' => $oldStatusLabel,
            'new_status_label' => $newStatusLabel,
            'message' => 'Order #' . $this->order->order_number . ' status updated from ' . $oldStatusLabel . ' to ' . $newStatusLabel,
            'url' => $url,
        ];
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Pending',
            'preparing' => 'Preparing',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'no_show' => 'No Show',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function getNotificationUrl($notifiable)
    {
        if ($notifiable->hasRole('customer')) {
            return route('livewire.customer.orders');
        }

        if ($notifiable->hasRole('employee')) {
            return route('livewire.employee.orders');
        }

        if ($notifiable->hasRole('owner') && $this->order->branch_id) {
            return route('livewire.owner.branches.branch-orders', ['branchId' => $this->order->branch_id]);
        }

        if ($notifiable->hasRole('super_admin')) {
            return route('livewire.admin.pages.shops.view-shops');
        }

        return route('livewire.customer.dashboard');
    }

    private function getPaymentMethodLabel($method)
    {
        return match ($method) {
            'gcash' => 'GCash',
            'paymaya' => 'PayMaya',
            'paymongo' => 'PayMongo',
            'pickup_payment' => 'Cash on Pickup',
            default => ucfirst(str_replace('_', ' ', $method)),
        };
    }
}
