<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountOtpNotification extends Notification
{
    use Queueable;

    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email — Bakeshop Account')
            ->greeting('Welcome! 🎉')
            ->line('Thanks for signing up. Please verify your email address to activate your account.')
            ->line('Your 6-digit verification code is:')
            ->line('**' . $this->code . '**')
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not create this account, you can safely ignore this email.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');
    }

    public function toArray($notifiable)
    {
        return [
            'code' => $this->code,
        ];
    }
}
