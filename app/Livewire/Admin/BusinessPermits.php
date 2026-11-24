<?php

namespace App\Livewire\Admin;

use App\Models\Shop;
use App\Notifications\BusinessPermitNotification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BusinessPermits extends Component
{
    public string $search = '';
    public bool $showReviewModal = false;
    public ?int $selectedShopId = null;
    public string $reviewExpiryDate = '';
    public string $rejectionNote = '';

    public function openReview(int $shopId): void
    {
        $shop = Shop::findOrFail($shopId);
        abort_unless(
            $shop->pending_business_permit_path
                && $shop->business_permit_review_status === 'pending_review',
            404
        );

        $this->selectedShopId = $shop->id;
        $this->reviewExpiryDate = $shop->pending_business_permit_expires_at?->format('Y-m-d') ?? '';
        $this->rejectionNote = '';
        $this->resetErrorBag();
        $this->showReviewModal = true;
    }

    public function closeReview(): void
    {
        $this->showReviewModal = false;
        $this->selectedShopId = null;
        $this->reviewExpiryDate = '';
        $this->rejectionNote = '';
        $this->resetErrorBag();
    }

    public function approve(): void
    {
        $this->validate([
            'reviewExpiryDate' => 'required|date|after_or_equal:today',
        ]);

        $shop = Shop::findOrFail($this->selectedShopId);
        abort_unless(
            $shop->pending_business_permit_path
                && $shop->business_permit_review_status === 'pending_review',
            404
        );

        $previousPermitPath = $shop->business_permit_path;

        $shop->update([
            'business_permit_path' => $shop->pending_business_permit_path,
            'business_permit_expires_at' => $this->reviewExpiryDate,
            'pending_business_permit_path' => null,
            'pending_business_permit_expires_at' => null,
            'business_permit_review_status' => 'approved',
            'business_permit_review_note' => null,
            'business_permit_last_reminder_key' => null,
        ]);

        if ($previousPermitPath && $previousPermitPath !== $shop->business_permit_path) {
            Storage::disk('local')->delete($previousPermitPath);
        }

        $shop->refresh();
        if ($shop->user) {
            $shop->user->notify(new BusinessPermitNotification(
                $shop,
                'business_permit_approved',
                "Your business permit for {$shop->shop_name} has been approved."
            ));
        }

        $this->closeReview();
        session()->flash('message', 'The business permit renewal has been approved.');
    }

    public function reject(): void
    {
        $this->validate([
            'rejectionNote' => 'required|string|min:5|max:500',
        ]);

        $shop = Shop::findOrFail($this->selectedShopId);
        abort_unless(
            $shop->pending_business_permit_path
                && $shop->business_permit_review_status === 'pending_review',
            404
        );

        $shop->update([
            'business_permit_review_status' => 'rejected',
            'business_permit_review_note' => $this->rejectionNote,
        ]);

        if ($shop->user) {
            $shop->user->notify(new BusinessPermitNotification(
                $shop,
                'business_permit_rejected',
                "Your business permit submission for {$shop->shop_name} was not approved.",
                $this->rejectionNote
            ));
        }

        $this->closeReview();
        session()->flash('message', 'The permit was rejected. The owner can review the reason and submit a replacement.');
    }

    public function render()
    {
        $shops = Shop::with('user')
            ->when($this->search !== '', function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($shopQuery) use ($searchTerm) {
                    $shopQuery->where('shop_name', 'like', $searchTerm)
                        ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                            $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                        });
                });
            })
            ->orderByRaw("CASE WHEN business_permit_review_status = 'pending_review' THEN 0 ELSE 1 END")
            ->orderBy('shop_name')
            ->get();

        return view('livewire.admin.business-permits', [
            'shops' => $shops,
            'pendingReviewCount' => Shop::where('business_permit_review_status', 'pending_review')->count(),
            'selectedShop' => $this->selectedShopId ? Shop::find($this->selectedShopId) : null,
        ])->layout('components.layouts.admin');
    }
}
