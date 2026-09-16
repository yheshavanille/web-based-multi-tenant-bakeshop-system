<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
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
        return ['database'];
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
}
