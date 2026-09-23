<?php

namespace App\Notifications;

use App\Models\ProductReview;
use App\Models\ServiceReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewModerationKeptNotification extends Notification
{
    use Queueable;

    protected $review;
    protected $reviewType; // 'service' | 'product'
    protected $moderatorNotes;

    public function __construct($review, string $reviewType, ?string $moderatorNotes = null)
    {
        $this->review = $review;
        $this->reviewType = $reviewType;
        $this->moderatorNotes = $moderatorNotes;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $customerName = $this->review->customer->name ?? 'Anonymous';
        $shopName = $this->review->shop->shop_name ?? 'your shop';

        $data = [
            'type' => 'review_moderation_kept',
            'review_id' => $this->review->id,
            'review_type' => $this->reviewType,
            'customer_name' => $customerName,
            'shop_name' => $shopName,
            'rating' => $this->review->rating,
            'moderator_notes' => $this->moderatorNotes,
            'message' => '✅ Your flag on ' . $customerName . '\'s review was reviewed. The Super Admin has decided to keep the review visible.',
            'url' => route('livewire.owner.reviews-history'),
        ];

        return $data;
    }

    public function toMail($notifiable)
    {
        $customerName = $this->review->customer->name ?? 'Anonymous';

        $mail = (new MailMessage)
            ->subject('Review Flag Decision — Kept Visible')
            ->greeting('Hello, ' . ($notifiable->name ?? 'Shop Owner') . '.')
            ->line('You recently flagged a review on your shop for moderation.')
            ->line('After review, the Super Admin has decided to **keep the review visible**.')
            ->line('**Review Summary:**')
            ->line('👤 Reviewer: ' . $customerName)
            ->line('⭐ Rating: ' . $this->review->rating . '/5');

        if (!empty($this->review->review)) {
            $mail->line('📝 Review: "' . \Str::limit($this->review->review, 200) . '"');
        }

        if (!empty($this->moderatorNotes)) {
            $mail->line('---')
                ->line('**Note from the Super Admin:**')
                ->line('"' . $this->moderatorNotes . '"');
        }

        $mail->line('---')
            ->line('You can respond to the review publicly or reach out to the customer privately if needed.')
            ->action('View Reviews History', route('livewire.owner.reviews-history'))
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
