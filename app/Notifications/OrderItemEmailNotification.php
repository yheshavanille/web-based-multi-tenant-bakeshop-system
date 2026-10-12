<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderItemEmailNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $item;
    protected $newStatus;

    /**
     * @param Order $order
     * @param OrderItem $item
     * @param string $newStatus  Either 'ready_for_pickup' or 'completed'
     */
    public function __construct(Order $order, OrderItem $item, string $newStatus)
    {
        $this->order = $order;
        $this->item = $item;
        $this->newStatus = $newStatus;
    }

    /**
     * Only the mail channel — bell notifications are handled separately.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $customerName = $notifiable->name ?? 'Customer';
        $shopName = $this->order->shop->shop_name ?? 'the bakeshop';
        $branchName = $this->order->branch->name ?? 'the branch';
        $branchAddress = $this->order->branch->address ?? null;
        $productName = $this->item->product->name ?? 'Your item';
        $quantity = $this->item->quantity;
        $orderNumber = $this->order->order_number;
        $pickupTime = $this->item->pickup_time
            ? \Carbon\Carbon::parse($this->item->pickup_time)->format('M d, Y h:i A')
            : null;

        $isReady = $this->newStatus === 'ready_for_pickup';

        $subject = $isReady
            ? 'Your item is ready for pickup — ' . $shopName
            : 'Your item has been marked as completed — ' . $shopName;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hi ' . $customerName . ',')
            ->line($isReady
                ? 'Good news! The following item from your order is now ready for pickup:'
                : 'This item from your order has been marked as completed:')
            ->line('• ' . $productName . ' (Qty: ' . $quantity . ')')
            ->line('Order #: ' . $orderNumber)
            ->line('Shop: ' . $shopName)
            ->line('Branch: ' . $branchName);

        if ($branchAddress) {
            $mail->line('Address: ' . $branchAddress);
        }

        if ($pickupTime) {
            $mail->line('Pickup time: ' . $pickupTime);
        }

        if ($isReady) {
            $mail->line('Please bring your order number when you come to pick up your item.');
            $mail->action('View My Orders', route('livewire.customer.orders'));
        } else {
            $mail->line('Thank you for choosing ' . $shopName . '!');
        }

        $mail->line('If you have any questions, please contact the bakeshop directly through the platform.');

        $mail->salutation('Thank you,' . "\n" . 'Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }

    public function toArray($notifiable)
    {
        // Not used (no database channel), but required by the base class.
        return [];
    }
}
