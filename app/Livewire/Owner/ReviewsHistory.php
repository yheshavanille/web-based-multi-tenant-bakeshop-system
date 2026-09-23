<?php

namespace App\Livewire\Owner;

use App\Models\ProductReview;
use App\Models\ServiceReview;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReviewsHistory extends Component
{
    public $activeTab = 'service'; // 'service' or 'product'
    public $serviceReviews = [];
    public $productReviews = [];
    public $branchFilter = 'all';
    public $ratingFilter = 'all';
    public $branches = [];

    // ✅ NEW: Flag modal state
    public $showFlagModal = false;
    public $flaggingReviewId = null;
    public $flaggingReviewType = null; // 'service' | 'product'
    public $flag_reason = '';
    public $flag_notes = '';

    public function mount()
    {
        $shop = Auth::user()->shop;
        $this->branches = $shop->branches;
        $this->loadReviews();
    }

    public function loadReviews()
    {
        // Service reviews
        $serviceQuery = ServiceReview::where('shop_id', Auth::user()->shop->id)
            ->with(['customer', 'branch', 'flaggedBy', 'moderatedBy']);

        if ($this->branchFilter !== 'all') {
            $serviceQuery->where('branch_id', $this->branchFilter);
        }
        if ($this->ratingFilter !== 'all') {
            $serviceQuery->where('rating', $this->ratingFilter);
        }

        $this->serviceReviews = $serviceQuery->orderBy('created_at', 'desc')->get();

        // Product reviews
        $productQuery = ProductReview::where('shop_id', Auth::user()->shop->id)
            ->with(['customer', 'product', 'order', 'flaggedBy', 'moderatedBy']);

        if ($this->branchFilter !== 'all') {
            $productQuery->whereHas('order', function ($q) {
                $q->where('branch_id', $this->branchFilter);
            });
        }
        if ($this->ratingFilter !== 'all') {
            $productQuery->where('rating', $this->ratingFilter);
        }

        $this->productReviews = $productQuery->orderBy('created_at', 'desc')->get();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updatedBranchFilter()
    {
        $this->loadReviews();
    }

    public function updatedRatingFilter()
    {
        $this->loadReviews();
    }

    // ✅ NEW: Open the flag modal
    public function openFlagModal(int $reviewId, string $type)
    {
        $this->flaggingReviewId = $reviewId;
        $this->flaggingReviewType = $type;
        $this->flag_reason = '';
        $this->flag_notes = '';
        $this->showFlagModal = true;
        $this->resetErrorBag();
    }

    // ✅ NEW: Close the modal
    public function closeFlagModal()
    {
        $this->showFlagModal = false;
        $this->flaggingReviewId = null;
        $this->flaggingReviewType = null;
        $this->flag_reason = '';
        $this->flag_notes = '';
        $this->resetErrorBag();
    }

    // ✅ NEW: Submit the flag
    public function submitFlag()
    {
        $this->validate([
            'flag_reason' => 'required|string|in:Spam,Fake review,Inappropriate language,Competitor attack,Other',
            'flag_notes' => 'nullable|string|max:500',
        ], [
            'flag_reason.required' => 'Please select a reason for flagging.',
            'flag_reason.in' => 'Please select a valid reason.',
        ]);

        // Load the right review
        if ($this->flaggingReviewType === 'service') {
            $review = ServiceReview::where('shop_id', Auth::user()->shop->id)
                ->findOrFail($this->flaggingReviewId);
        } else {
            $review = ProductReview::where('shop_id', Auth::user()->shop->id)
                ->findOrFail($this->flaggingReviewId);
        }

        // Prevent flagging twice
        if ($review->moderation_status !== 'visible') {
            session()->flash('error', 'This review is already under review or has been moderated.');
            $this->closeFlagModal();
            return;
        }

        $review->update([
            'moderation_status' => 'pending_review',
            'flagged_by' => Auth::id(),
            'flag_reason' => $this->flag_reason,
            'flag_notes' => $this->flag_notes,
            'flagged_at' => now(),
        ]);

        $this->closeFlagModal();
        $this->loadReviews();

        session()->flash('message', 'Review flagged. The Super Admin will review it.');
    }

    public function render()
    {
        return view('livewire.owner.reviews-history')
            ->layout('components.layouts.owner');
    }
}
