<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    protected $changedAt;
    protected $ipAddress;
    protected $userAgent;

    public function __construct(?Carbon $changedAt = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->changedAt = $changedAt ?? now();
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'password_changed',
            'message' => 'Your password was changed on ' . $this->changedAt->format('M d, Y h:i A') . '. If this wasn\'t you, reset your password immediately.',
            'changed_at' => $this->changedAt->toIso8601String(),
            'changed_at_human' => $this->changedAt->format('M d, Y h:i A'),
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'url' => $this->getProfileUrl($notifiable),
        ];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your password was changed — Web-based Multi-Tenant Bakeshop')
            ->greeting('Hi ' . ($notifiable->name ?? 'there') . ',')
            ->line('This is a confirmation that your password was successfully changed on **' . $this->changedAt->format('M d, Y \a\t h:i A') . '**.')
            ->line('If you made this change, no action is needed — you can safely ignore this email.');

        if ($this->ipAddress || $this->userAgent) {
            $mail->line('---')->line('**Session details:**');
            if ($this->ipAddress) {
                $mail->line('🌐 IP Address: ' . $this->ipAddress);
            }
            if ($this->userAgent) {
                $mail->line('💻 Device: ' . $this->userAgent);
            }
        }

        $mail->line('---')
            ->line('⚠️ **If you did NOT change your password**, your account may be compromised.')
            ->line('Reset it immediately and contact support.')
            ->action('Reset My Password', route('livewire.auth.forgot-password'))
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }

    private function getProfileUrl($notifiable)
    {
        if ($notifiable->hasRole('super_admin')) {
            return route('livewire.admin.profile');
        }

        if ($notifiable->hasRole('employee')) {
            return route('livewire.employee.profile');
        }

        if ($notifiable->hasRole('owner')) {
            return route('livewire.owner.dashboard');
        }

        if ($notifiable->hasRole('customer')) {
            return route('livewire.customer.profile');
        }

        return url('/');
    }
}
