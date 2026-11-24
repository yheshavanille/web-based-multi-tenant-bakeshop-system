<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BusinessPermitNotification extends Notification
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
        $isAdminNotification = $this->type === 'business_permit_submitted';

        return [
            'type' => $this->type,
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->shop_name,
            'permit_expires_at' => $this->shop->business_permit_expires_at?->format('Y-m-d'),
            'submitted_expiry_date' => $this->shop->pending_business_permit_expires_at?->format('Y-m-d'),
            'review_note' => $this->reviewNote,
            'message' => $this->message,
            'url' => $isAdminNotification
                ? route('livewire.admin.business-permits')
                : route('livewire.owner.business-permit'),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $isAdminNotification = $this->type === 'business_permit_submitted';

        $mail = (new MailMessage())
            ->subject($this->mailSubject())
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line($this->message)
            ->line('Shop: ' . $this->shop->shop_name);

        if ($this->shop->business_permit_expires_at) {
            $mail->line('Current permit expiry date: ' . $this->shop->business_permit_expires_at->format('M d, Y'));
        }

        if ($this->shop->pending_business_permit_expires_at) {
            $mail->line('Submitted permit expiry date: ' . $this->shop->pending_business_permit_expires_at->format('M d, Y'));
        }

        if ($this->reviewNote) {
            $mail->line('Admin note: ' . $this->reviewNote);
        }

        return $mail->action(
            $isAdminNotification ? 'Review Business Permits' : 'View Business Permit',
            $isAdminNotification
                ? route('livewire.admin.business-permits')
                : route('livewire.owner.business-permit')
        )->line('This is an automated business permit update from the bakeshop system.');
    }

    private function mailSubject(): string
    {
        return match ($this->type) {
            'business_permit_submitted' => 'Business permit renewal submitted',
            'business_permit_approved' => 'Business permit renewal approved',
            'business_permit_rejected' => 'Business permit renewal needs changes',
            'business_permit_expiry_reminder' => 'Business permit expiry reminder',
            'business_permit_expired' => 'Business permit has expired',
            default => 'Business permit update',
        };
    }
}
