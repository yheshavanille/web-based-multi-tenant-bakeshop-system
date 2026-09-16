<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
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
        return ['database'];
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
}
