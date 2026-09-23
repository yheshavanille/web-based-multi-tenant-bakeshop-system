<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopDeletedByAdminNotification extends Notification
{
    use Queueable;

    protected $shop;
    protected $reason;

    public function __construct(Shop $shop, string $reason = '')
    {
        $this->shop = $shop;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        // ✅ Send to both database (bell) and email
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'shop_deleted_by_admin',
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->shop_name,
            'reason' => $this->reason,
            'message' => 'Your shop "' . $this->shop->shop_name . '" has been deleted by the Super Admin.',
            'url' => route('livewire.customer.start-selling'),
        ];
    }

    // ✅ NEW: email to the shop owner
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Shop Deleted — ' . $this->shop->shop_name)
            ->greeting('Hello, ' . ($notifiable->name ?? 'Shop Owner') . '.')
            ->line('We regret to inform you that your shop **' . $this->shop->shop_name . '** has been deleted by the Super Admin.')
            ->line('**Shop Details:**')
            ->line('🏪 Shop Name: ' . $this->shop->shop_name)
            ->line('📍 Address: ' . ($this->shop->address ?? 'N/A'));

        if (!empty($this->reason)) {
            $mail->line('---')
                ->line('**Reason for deletion:**')
                ->line($this->reason);
        }

        $mail->line('---')
            ->line('If you believe this was a mistake or would like to reopen your shop in the future, you can reapply as a seller.')
            ->action('Reapply as a Seller', route('livewire.customer.start-selling'))
            ->line('If you need clarification, please contact our support team.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
