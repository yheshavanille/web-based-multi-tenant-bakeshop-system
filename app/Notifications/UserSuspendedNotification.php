<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSuspendedNotification extends Notification
{
    use Queueable;

    protected $targetUser;
    protected $reason;
    protected $byName;

    public function __construct(User $targetUser, string $reason, string $byName = 'Super Admin')
    {
        $this->targetUser = $targetUser;
        $this->reason = $reason;
        $this->byName = $byName;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'user_suspended',
            'user_id' => $this->targetUser->id,
            'user_name' => $this->targetUser->name,
            'user_email' => $this->targetUser->email,
            'reason' => $this->reason,
            'by' => $this->byName,
            'message' => 'Your account has been suspended by the Super Admin.',
            'url' => route('livewire.auth.login'),
        ];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your Account Has Been Suspended')
            ->greeting('Hello, ' . ($this->targetUser->name ?? 'User') . '.')
            ->line('We are writing to inform you that your account has been **suspended** by the Super Admin.')
            ->line('While suspended, you will not be able to log in or use your account.');

        if (!empty($this->reason)) {
            $mail->line('---')
                ->line('**Reason for suspension:**')
                ->line('"' . $this->reason . '"');
        }

        $mail->line('---')
            ->line('If you believe this was a mistake, please contact our support team.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
