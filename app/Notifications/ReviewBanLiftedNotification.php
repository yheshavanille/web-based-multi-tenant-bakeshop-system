<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewBanLiftedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'review_ban_lifted',
            'is_customer' => true,
            'message' => 'Your review ban has been lifted. You can now leave reviews and ratings again.',
            'url' => route('livewire.customer.orders'),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your review ban has been lifted — Web-based Multi-Tenant Bakeshop')
            ->greeting('Hi ' . ($notifiable->name ?? 'there') . ',')
            ->line('Good news — the Super Admin has lifted your review ban.')
            ->line('You can now leave reviews and ratings for bakeshops and products again.')
            ->line('Please keep our community guidelines in mind when leaving future reviews.')
            ->action('Browse Bakeshops', route('livewire.customer.dashboard'))
            ->salutation('— Web-based Multi-Tenant Bakeshop System');
    }
}
