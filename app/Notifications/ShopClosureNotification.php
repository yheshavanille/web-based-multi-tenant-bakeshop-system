<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopClosureNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Shop $shop,
        private string $type,
        private string $message,
        private ?string $reviewNote = null
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        $isAdminNotification = $this->type === 'shop_closure_requested';

        return [
            'type' => $this->type,
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->shop_name,
            'reason' => $this->shop->closure_request_reason,
            'review_note' => $this->reviewNote,
            'message' => $this->message,
            'url' => $isAdminNotification
                ? route('livewire.admin.pages.shops.view-shops')
                : ($this->type === 'shop_closure_approved'
                    ? route('livewire.customer.dashboard')
                    : route('livewire.owner.shop.edit-shop')),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $isAdminNotification = $this->type === 'shop_closure_requested';
        $isApproved = $this->type === 'shop_closure_approved';

        $mail = (new MailMessage())
            ->subject($this->mailSubject())
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line($this->message)
            ->line('Shop: ' . $this->shop->shop_name);

        if ($this->shop->closure_request_reason) {
            $mail->line('Owner reason: ' . $this->shop->closure_request_reason);
        }

        if ($this->reviewNote) {
            $mail->line('Admin note: ' . $this->reviewNote);
        }

        return $mail->action(
            $isAdminNotification ? 'Review Shop Closure Request' : ($isApproved ? 'Go to Dashboard' : 'Edit Shop'),
            $isAdminNotification
                ? route('livewire.admin.pages.shops.view-shops')
                : ($isApproved
                    ? route('livewire.customer.dashboard')
                    : route('livewire.owner.shop.edit-shop'))
        )->line('This is an automated shop closure update from the bakeshop system.');
    }

    private function mailSubject(): string
    {
        return match ($this->type) {
            'shop_closure_requested' => 'Shop closure request submitted',
            'shop_closure_approved' => 'Shop closure request approved',
            'shop_closure_rejected' => 'Shop closure request not approved',
            default => 'Shop closure update',
        };
    }
}
