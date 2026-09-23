<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRestoredNotification extends Notification
{
    use Queueable;

    protected $targetUser;

    public function __construct(User $targetUser)
    {
        $this->targetUser = $targetUser;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'user_restored',
            'user_id' => $this->targetUser->id,
            'user_name' => $this->targetUser->name,
            'user_email' => $this->targetUser->email,
            'message' => 'Your account has been restored by the Super Admin. You can now log in again.',
            'url' => route('livewire.auth.login'),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Restored')
            ->greeting('Good news, ' . ($this->targetUser->name ?? 'User') . '!')
            ->line('Your account has been **restored** by the Super Admin.')
            ->line('You can now log in and use your account as usual.')
            ->line('Welcome back, and we wish you continued success!')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');
    }
}
