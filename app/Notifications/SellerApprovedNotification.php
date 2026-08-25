<?php

namespace App\Notifications;

use App\Models\SellerRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SellerApprovedNotification extends Notification
{
    use Queueable;

    protected $application;
    protected $requirements;
    protected $customNote;

    public function __construct(SellerRegistration $application, $requirements, $customNote = null)
    {
        $this->application = $application;
        $this->requirements = $requirements;
        $this->customNote = $customNote;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $requirementsList = [];

        foreach ($this->requirements as $key => $value) {
            $labels = [
                'valid_id' => 'Valid Government ID',
                'business_permit' => 'Business Permit',
                'shop_name' => 'Shop Name',
                'shop_address' => 'Shop Address',
                'contact_number' => 'Contact Number',
            ];

            $requirementsList[] = [
                'label' => $labels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
                'met' => $value,
            ];
        }

        $data = [
            'type' => 'seller_approved',
            'shop_name' => $this->application->shop_name,
            'message' => '🎉 Your seller application for "' . $this->application->shop_name . '" has been approved! You can now start selling.',
            'requirements' => $requirementsList,
            'url' => route('livewire.owner.dashboard'),
        ];

        if ($this->customNote) {
            $data['custom_note'] = $this->customNote;
        }

        return $data;
    }
}
