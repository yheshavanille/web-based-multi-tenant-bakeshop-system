<?php

namespace App\Notifications;

use App\Models\SellerRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        // ✅ Send to both database (bell) and email
        return ['database', 'mail'];
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

    // ✅ NEW: send the same content as an email
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Update on Your Seller Application — ' . $this->application->shop_name)
            ->greeting('Hello, ' . ($notifiable->name ?? 'Applicant') . '.')
            ->line('Thank you for your interest in becoming a seller on our platform.')
            ->line('After careful review, we regret to inform you that your application for **' . $this->application->shop_name . '** has not been approved at this time.')
            ->line('**Reason for rejection:**')
            ->line($this->rejectionReason);

        // ✅ Append the custom note if the Super Admin wrote one
        if (!empty($this->customNote)) {
            $mail->line('---')
                ->line('**Additional note from the Super Admin:**')
                ->line('"' . $this->customNote . '"');
        }

        $mail->line('---')
            ->line('You are welcome to review our requirements and reapply whenever you are ready.')
            ->action('Reapply as a Seller', route('livewire.customer.start-selling'))
            ->line('If you believe this was a mistake, please contact our support team.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
