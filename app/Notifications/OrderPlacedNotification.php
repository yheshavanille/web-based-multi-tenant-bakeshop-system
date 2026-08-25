<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // ✅ Determine the correct URL based on the user's role
        $url = $this->getNotificationUrl($notifiable);

        // ✅ Get payment method label
        $paymentMethod = $this->order->payment_method ?? 'N/A';
        $paymentMethodLabel = $this->getPaymentMethodLabel($paymentMethod);

        return [
            'type' => 'new_order',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->customer->name,
            'shop_name' => $this->order->shop->shop_name,
            'total_amount' => $this->order->total_amount,
            'branch_id' => $this->order->branch_id,
            'payment_method' => $paymentMethod,
            'payment_method_label' => $paymentMethodLabel,
            'payment_status' => $this->order->payment_status,
            'message' => 'New order #' . $this->order->order_number . ' placed by ' . $this->order->customer->name,
            'url' => $url,
        ];
    }

    private function getNotificationUrl($notifiable)
    {
        if ($notifiable->hasRole('employee')) {
            return route('livewire.employee.orders');
        }

        if ($notifiable->hasRole('owner') && $this->order->branch_id) {
            return route('livewire.owner.branches.branch-orders', ['branchId' => $this->order->branch_id]);
        }

        if ($notifiable->hasRole('customer')) {
            return route('livewire.customer.orders');
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
