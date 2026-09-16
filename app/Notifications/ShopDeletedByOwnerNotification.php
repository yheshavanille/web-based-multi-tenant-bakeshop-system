<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
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
        return ['database'];
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
}
