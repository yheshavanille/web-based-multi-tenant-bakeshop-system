<?php

namespace App\Notifications;

use App\Models\SellerRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        // ✅ Send to both database (bell) and email
        return ['database', 'mail'];
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

    // ✅ NEW: send the same content as an email
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('🎉 Seller Application Approved — ' . $this->application->shop_name)
            ->greeting('Congratulations, ' . ($notifiable->name ?? 'Seller') . '!')
            ->line('Your seller application for **' . $this->application->shop_name . '** has been approved.')
            ->line('You can now start selling on the Web-based Multi-Tenant Bakeshop platform.')
            ->line('**Shop Details:**')
            ->line('🏪 Shop Name: ' . $this->application->shop_name)
            ->line('📍 Address: ' . ($this->application->shop_address ?? 'N/A'))
            ->line('📞 Contact: ' . ($this->application->contact_number ?? 'N/A'));

        // ✅ Append the custom note if the Super Admin wrote one
        if (!empty($this->customNote)) {
            $mail->line('---')
                ->line('**Message from the Super Admin:**')
                ->line('"' . $this->customNote . '"');
        }

        $mail->action('Go to Your Shop Dashboard', route('livewire.owner.dashboard'))
            ->line('Thank you for joining our platform. We wish you success with your bakeshop!')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
