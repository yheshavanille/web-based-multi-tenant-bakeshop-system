<?php

namespace App\Notifications;

use App\Models\SellerRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SellerRejectedNotification extends Notification
{
    use Queueable;

    protected $application;
    protected $requirements;
    protected $rejectionReason;
    protected $customNote;

    public function __construct(SellerRegistration $application, $requirements, $rejectionReason, $customNote = null)
    {
        $this->application = $application;
        $this->requirements = $requirements;
        $this->rejectionReason = $rejectionReason;
        $this->customNote = $customNote;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $requirementsList = [];
        $missingCount = 0;

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

            if (!$value) {
                $missingCount++;
            }
        }

        $data = [
            'type' => 'seller_rejected',
            'shop_name' => $this->application->shop_name,
            'message' => '❌ Your seller application for "' . $this->application->shop_name . '" has been rejected.',
            'requirements' => $requirementsList,
            'missing_count' => $missingCount,
            'rejection_reason' => $this->rejectionReason,
            'url' => route('livewire.customer.start-selling'),
        ];

        if ($this->customNote) {
            $data['custom_note'] = $this->customNote;
        }

        return $data;
    }
}
