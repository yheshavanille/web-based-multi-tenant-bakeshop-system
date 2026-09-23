<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopRestoredByAdminNotification extends Notification
{
    use Queueable;

    protected $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function via($notifiable)
    {
        // ✅ Send to both database (bell) and email
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'shop_restored_by_admin',
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->shop_name,
            'message' => 'Your shop "' . $this->shop->shop_name . '" has been restored by the Super Admin.',
            'url' => route('livewire.owner.dashboard'),
        ];
    }

    // ✅ NEW: email to the shop owner — short and sweet
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Your Shop Has Been Restored — ' . $this->shop->shop_name)
            ->greeting('Good news, ' . ($notifiable->name ?? 'Shop Owner') . '!')
            ->line('Your shop **' . $this->shop->shop_name . '** has been restored by the Super Admin.')
            ->line('You can now log back in and manage your bakeshop as usual.')
            ->action('Go to Your Shop Dashboard', route('livewire.owner.dashboard'))
            ->line('Welcome back, and we wish you continued success!')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');
    }
}
