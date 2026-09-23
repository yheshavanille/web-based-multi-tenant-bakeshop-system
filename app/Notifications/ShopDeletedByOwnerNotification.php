<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopDeletedByOwnerNotification extends Notification
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
            'type' => 'shop_deleted_by_owner',
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->shop_name,
            'owner_name' => $this->shop->user->name ?? 'Unknown Owner',
            'owner_email' => $this->shop->user->email ?? 'N/A',
            'reason' => $this->reason,
            'message' => 'Owner "' . ($this->shop->user->name ?? 'Unknown') . '" deleted their shop "' . $this->shop->shop_name . '"',
            'url' => route('livewire.admin.pages.shops.view-shops'),
        ];
    }

    // ✅ NEW: email to Super Admins
    public function toMail($notifiable)
    {
        $ownerName = $this->shop->user->name ?? 'Unknown Owner';
        $ownerEmail = $this->shop->user->email ?? 'N/A';

        $mail = (new MailMessage)
            ->subject('🏪 Shop Deleted by Owner — ' . $this->shop->shop_name)
            ->greeting('Hello, Super Admin.')
            ->line('A bakeshop owner has deleted their shop from the platform.')
            ->line('**Deletion Summary:**')
            ->line('🏪 Shop Name: ' . $this->shop->shop_name)
            ->line('👤 Owner: ' . $ownerName)
            ->line('📧 Email: ' . $ownerEmail)
            ->line('📍 Address: ' . ($this->shop->address ?? 'N/A'));

        if (!empty($this->reason)) {
            $mail->line('---')
                ->line('**Reason given by the owner:**')
                ->line('"' . $this->reason . '"');
        }

        $mail->line('---')
            ->line('The shop has been moved to the deleted list and can be restored from the View Shops page if needed.')
            ->action('Review Deleted Shops', route('livewire.admin.pages.shops.view-shops'))
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
