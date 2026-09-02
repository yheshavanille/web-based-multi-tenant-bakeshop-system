<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OutOfStockNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $branch;

    public function __construct(Product $product, Branch $branch)
    {
        $this->product = $product;
        $this->branch = $branch;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock', // Reuse same type for consistency
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'branch_name' => $this->branch->name,
            'stock' => 0,
            'is_out_of_stock' => true,
            'message' => '🚫 ' . $this->product->name . ' is OUT OF STOCK at ' . $this->branch->name,
            'url' => route('livewire.employee.inventory'),
        ];
    }
}
