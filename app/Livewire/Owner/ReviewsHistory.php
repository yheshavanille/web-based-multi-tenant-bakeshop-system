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
            ->with(['customer', 'branch']);

        if ($this->branchFilter !== 'all') {
            $serviceQuery->where('branch_id', $this->branchFilter);
        }
        if ($this->ratingFilter !== 'all') {
            $serviceQuery->where('rating', $this->ratingFilter);
        }

        $this->serviceReviews = $serviceQuery->orderBy('created_at', 'desc')->get();

        // Product reviews
        $productQuery = ProductReview::where('shop_id', Auth::user()->shop->id)
            ->with(['customer', 'product', 'order']);

        // Apply branch filter (via order branch)
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

    public function render()
    {
        return view('livewire.owner.reviews-history')
            ->layout('components.layouts.owner');
    }
}
