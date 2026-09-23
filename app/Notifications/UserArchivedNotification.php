<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserArchivedNotification extends Notification
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
            'type' => 'user_archived',
            'user_id' => $this->targetUser->id,
            'user_name' => $this->targetUser->name,
            'user_email' => $this->targetUser->email,
            'reason' => $this->reason,
            'by' => $this->byName,
            'message' => 'Your account has been archived by the Super Admin.',
            'url' => route('livewire.auth.login'),
        ];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your Account Has Been Archived')
            ->greeting('Hello, ' . ($this->targetUser->name ?? 'User') . '.')
            ->line('We are writing to inform you that your account has been **archived** by the Super Admin.')
            ->line('While archived, you will not be able to log in or use your account. Your data has been preserved.');

        if (!empty($this->reason)) {
            $mail->line('---')
                ->line('**Reason for archiving:**')
                ->line('"' . $this->reason . '"');
        }

        $mail->line('---')
            ->line('If you believe this was a mistake, please contact our support team.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
