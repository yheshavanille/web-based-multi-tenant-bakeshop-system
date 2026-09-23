<?php

namespace App\Livewire\Admin;

use App\Models\ProductReview;
use App\Models\ServiceReview;
use App\Models\User;
use App\Notifications\ReviewModerationKeptNotification;
use App\Notifications\ReviewModerationRemovedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class FlaggedReviews extends Component
{
    public $activeTab = 'service';
    public $serviceReviews = [];
    public $productReviews = [];
    public $search = '';

    public $showModerationModal = false;
    public $moderatingReviewId = null;
    public $moderatingReviewType = null;
    public $moderator_notes = '';
    public $selectedAction = '';

    public function mount()
    {
        $this->loadFlagged();
    }

    public function loadFlagged()
    {
        $this->serviceReviews = ServiceReview::where('moderation_status', 'pending_review')
            ->with(['customer', 'shop', 'branch', 'flaggedBy'])
            ->orderBy('flagged_at', 'desc')
            ->get();

        $this->productReviews = ProductReview::where('moderation_status', 'pending_review')
            ->with(['customer', 'shop', 'product', 'flaggedBy'])
            ->orderBy('flagged_at', 'desc')
            ->get();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openModerationModal(int $id, string $type)
    {
        $this->moderatingReviewId = $id;
        $this->moderatingReviewType = $type;
        $this->moderator_notes = '';
        $this->selectedAction = '';
        $this->showModerationModal = true;
        $this->resetErrorBag();
    }

    public function closeModerationModal()
    {
        $this->showModerationModal = false;
        $this->moderatingReviewId = null;
        $this->moderatingReviewType = null;
        $this->moderator_notes = '';
        $this->selectedAction = '';
        $this->resetErrorBag();
    }

    public function decide(string $action)
    {
        $this->validate([
            'moderator_notes' => 'nullable|string|max:500',
        ]);

        if (!in_array($action, ['keep', 'remove', 'ban'])) {
            return;
        }

        if ($this->moderatingReviewType === 'service') {
            $review = ServiceReview::findOrFail($this->moderatingReviewId);
        } else {
            $review = ProductReview::findOrFail($this->moderatingReviewId);
        }

        $customer = $review->customer;
        $owner = $review->flaggedBy; // the shop owner who flagged the review

        // ✅ Apply decision
        if ($action === 'keep') {
            $review->update([
                'moderation_status' => 'kept',
                'moderated_by' => Auth::id(),
                'moderator_notes' => $this->moderator_notes,
                'moderated_at' => now(),
            ]);

            // ✅ Notify owner — flag was reviewed, review stays
            if ($owner) {
                Notification::send(
                    $owner,
                    new ReviewModerationKeptNotification($review, $this->moderatingReviewType, $this->moderator_notes)
                );
            }
        } elseif ($action === 'remove') {
            $review->update([
                'moderation_status' => 'removed',
                'moderated_by' => Auth::id(),
                'moderator_notes' => $this->moderator_notes,
                'moderated_at' => now(),
            ]);

            // ✅ Notify owner + customer
            $recipients = collect();
            if ($owner) $recipients->push($owner);
            if ($customer) $recipients->push($customer);

            if ($recipients->count() > 0) {
                Notification::send(
                    $recipients,
                    new ReviewModerationRemovedNotification($review, $this->moderatingReviewType, $this->moderator_notes, false)
                );
            }
        } elseif ($action === 'ban') {
            // Remove the review
            $review->update([
                'moderation_status' => 'removed',
                'moderated_by' => Auth::id(),
                'moderator_notes' => $this->moderator_notes,
                'moderated_at' => now(),
            ]);

            // Ban the customer
            if ($customer) {
                $customer->update([
                    'review_banned_at' => now(),
                    'review_ban_reason' => $this->moderator_notes ?: 'Flagged review abuse',
                ]);
            }

            // ✅ Notify owner + customer (with banned flag)
            $recipients = collect();
            if ($owner) $recipients->push($owner);
            if ($customer) $recipients->push($customer);

            if ($recipients->count() > 0) {
                Notification::send(
                    $recipients,
                    new ReviewModerationRemovedNotification($review, $this->moderatingReviewType, $this->moderator_notes, true)
                );
            }
        }

        $this->closeModerationModal();
        $this->loadFlagged();

        session()->flash('message', match ($action) {
            'keep' => 'Review kept. The owner has been notified.',
            'remove' => 'Review removed. The owner and customer have been notified.',
            'ban' => 'Review removed and the customer has been banned. Both parties have been notified.',
        });
    }

    public function render()
    {
        return view('livewire.admin.flagged-reviews')
            ->layout('components.layouts.admin');
    }
}
