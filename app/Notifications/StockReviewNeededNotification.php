<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockReviewNeededNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $item;
    protected $reason; // 'cancelled_by_customer' | 'cancelled_by_staff' | 'no_show'

    public function __construct(Order $order, OrderItem $item, string $reason)
    {
        $this->order = $order;
        $this->item = $item;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $reasonLabel = match ($this->reason) {
            'cancelled_by_customer' => 'cancelled by customer',
            'cancelled_by_staff'    => 'cancelled by staff',
            'no_show'               => 'marked as no-show',
            default                 => 'cancelled',
        };

        $productName = $this->item->product->name ?? 'Product';
        $branchName = $this->item->branch->name ?? 'Branch';

        return [
            'type'         => 'stock_review_needed',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'product_id'   => $this->item->product_id,
            'product_name' => $productName,
            'branch_id'    => $this->item->branch_id,
            'branch_name'  => $branchName,
            'quantity'     => $this->item->quantity,
            'reason'       => $this->reason,
            'reason_label' => $reasonLabel,
            'message'      => 'Order ' . $this->order->order_number . ' was ' . $reasonLabel . '. Please verify if stock for ' . $productName . ' (' . $branchName . ') should be restored.',
            'url'          => route('livewire.employee.inventory'),
        ];
    }
}
