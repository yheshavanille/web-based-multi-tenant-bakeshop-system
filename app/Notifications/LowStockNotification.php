<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $branch;
    protected $stock;

    public function __construct(Product $product, Branch $branch, $stock)
    {
        $this->product = $product;
        $this->branch = $branch;
        $this->stock = $stock;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'branch_name' => $this->branch->name,
            'stock' => $this->stock,
            'message' => 'Low stock alert: ' . $this->product->name . ' has only ' . $this->stock . ' left at ' . $this->branch->name,
            'url' => route('livewire.employee.inventory'),
        ];
    }
}
