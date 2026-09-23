<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewModerationRemovedNotification extends Notification
{
    use Queueable;

    protected $review;
    protected $reviewType; // 'service' | 'product'
    protected $moderatorNotes;
    protected $banned;

    public function __construct($review, string $reviewType, ?string $moderatorNotes = null, bool $banned = false)
    {
        $this->review = $review;
        $this->reviewType = $reviewType;
        $this->moderatorNotes = $moderatorNotes;
        $this->banned = $banned;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $customerName = $this->review->customer->name ?? 'Anonymous';
        $shopName = $this->review->shop->shop_name ?? 'your shop';

        // ✅ Choose message based on who receives it
        $isCustomer = $notifiable->id === $this->review->customer_id;

        if ($this->banned) {
            $message = $isCustomer
                ? '🚫 Your review has been removed and you have been banned from submitting future reviews.'
                : '🚫 Your flag on ' . $customerName . '\'s review was reviewed. The review was removed and the reviewer was banned.';
        } else {
            $message = $isCustomer
                ? '❌ Your review on ' . $shopName . ' has been removed by the Super Admin.'
                : '❌ Your flag on ' . $customerName . '\'s review was reviewed. The review has been removed.';
        }

        return [
            'type' => $this->banned ? 'review_moderation_banned' : 'review_moderation_removed',
            'review_id' => $this->review->id,
            'review_type' => $this->reviewType,
            'customer_name' => $customerName,
            'shop_name' => $shopName,
            'rating' => $this->review->rating,
            'moderator_notes' => $this->moderatorNotes,
            'banned' => $this->banned,
            'is_customer' => $isCustomer,
            'message' => $message,
            'url' => $isCustomer
                ? route('livewire.customer.orders')
                : route('livewire.owner.reviews-history'),
        ];
    }

    public function toMail($notifiable)
    {
        $isCustomer = $notifiable->id === $this->review->customer_id;
        $customerName = $this->review->customer->name ?? 'Anonymous';
        $shopName = $this->review->shop->shop_name ?? 'the shop';

        if ($isCustomer) {
            return $this->customerMail($notifiable, $shopName);
        }

        return $this->ownerMail($notifiable, $customerName);
    }

    private function ownerMail($notifiable, string $customerName): MailMessage
    {
        $subject = $this->banned
            ? 'Flag Decision — Review Removed & Reviewer Banned'
            : 'Flag Decision — Review Removed';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello, ' . ($notifiable->name ?? 'Shop Owner') . '.')
            ->line('You recently flagged a review on your shop for moderation.')
            ->line($this->banned
                ? 'After review, the Super Admin **removed the review** and **banned the reviewer** from submitting future reviews.'
                : 'After review, the Super Admin has **removed the review**.')
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
            ->action('View Reviews History', route('livewire.owner.reviews-history'))
            ->line('Thank you for helping keep our platform trustworthy.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }

    private function customerMail($notifiable, string $shopName): MailMessage
    {
        $subject = $this->banned
            ? 'Your Review Was Removed — Account Restricted'
            : 'Your Review Was Removed';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello, ' . ($notifiable->name ?? 'Customer') . '.')
            ->line('Your recent review on **' . $shopName . '** has been reviewed by our moderation team.')
            ->line($this->banned
                ? 'After review, the review has been **removed** and your ability to submit new reviews has been **revoked** due to a violation of our community guidelines.'
                : 'After review, the review has been **removed** because it did not meet our community guidelines.')
            ->line('**Review Summary:**')
            ->line('⭐ Rating you left: ' . $this->review->rating . '/5');

        if (!empty($this->review->review)) {
            $mail->line('📝 Review: "' . \Str::limit($this->review->review, 200) . '"');
        }

        if (!empty($this->moderatorNotes)) {
            $mail->line('---')
                ->line('**Reason from the Super Admin:**')
                ->line('"' . $this->moderatorNotes . '"');
        }

        if ($this->banned) {
            $mail->line('---')
                ->line('If you believe this was a mistake, please contact our support team.');
        } else {
            $mail->line('---')
                ->line('You are still welcome to submit reviews on future orders — just please follow our community guidelines.');
        }

        $mail->action('View My Orders', route('livewire.customer.orders'))
            ->salutation('— Web-based Multi-Tenant Bakeshop System');

        return $mail;
    }
}
