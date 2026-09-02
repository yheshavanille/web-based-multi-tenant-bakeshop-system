<?php

namespace App\Notifications;

use App\Models\SellerRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSellerRegistrationNotification extends Notification
{
    use Queueable;

    protected $registration;

    public function __construct(SellerRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_seller_registration',
            'registration_id' => $this->registration->id,
            'applicant_name' => $this->registration->user->name ?? 'N/A',
            'business_name' => $this->registration->shop_name ?? 'N/A',
            'business_address' => $this->registration->shop_address ?? 'N/A',
            'contact_number' => $this->registration->contact_number ?? 'N/A',
            'message' => 'New seller application from ' . ($this->registration->user->name ?? 'Unknown'),
            'url' => route('livewire.admin.pending-sellers'),
        ];
    }
}
