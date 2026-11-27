<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Shop;
use App\Notifications\ShopClosureNotification;
use App\Notifications\ShopDeletedByAdminNotification;
use App\Notifications\ShopRestoredByAdminNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Livewire\Component;

class ViewShops extends Component
{
    public $showDeleted = false;
    public $search = '';

    //  NEW: Delete reason modal state
    public $showDeleteModal = false;
    public $shopToDeleteId = null;
    public $deleteReason = '';
    public bool $showClosureReviewModal = false;
    public ?int $closureShopId = null;
    public string $closureReviewNote = '';

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    public function updatedSearch()
    {
        // This triggers the render to update
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    //  NEW: Open the delete confirmation modal
    public function openDeleteModal(int $shopId)
    {
        $this->shopToDeleteId = $shopId;
        $this->deleteReason = '';
        $this->showDeleteModal = true;
        $this->resetErrorBag();
    }

    //  NEW: Close the modal
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->shopToDeleteId = null;
        $this->deleteReason = '';
        $this->resetErrorBag();
    }

    public function openClosureReviewModal(int $shopId): void
    {
        $shop = Shop::findOrFail($shopId);
        abort_unless($shop->closure_request_status === 'pending', 404);

        $this->closureShopId = $shop->id;
        $this->closureReviewNote = '';
        $this->showClosureReviewModal = true;
        $this->resetErrorBag();
    }

    public function closeClosureReviewModal(): void
    {
        $this->showClosureReviewModal = false;
        $this->closureShopId = null;
        $this->closureReviewNote = '';
        $this->resetErrorBag();
    }

    public function approveClosureRequest(int $shopId): void
    {
        $shop = Shop::with('user')->findOrFail($shopId);
        abort_unless($shop->closure_request_status === 'pending', 404);

        if ($shop->hasUnresolvedOrders()) {
            session()->flash('error', 'This shop has unresolved orders or payments. Resolve them before approving closure.');
            return;
        }

        $shop->update([
            'closure_request_status' => 'approved',
            'closure_reviewed_at' => now(),
            'closure_review_note' => null,
        ]);
        $shop->delete();

        $owner = $shop->user;
        if ($owner && $owner->hasRole('owner')) {
            $owner->removeRole('owner');
        }

        if ($owner) {
            $owner->notify(new ShopClosureNotification(
                $shop,
                'shop_closure_approved',
                "The closure request for {$shop->shop_name} was approved. The shop has been closed."
            ));
        }

        session()->flash('message', 'Closure request approved. The shop was archived.');
    }

    public function rejectClosureRequest(): void
    {
        $this->validate([
            'closureReviewNote' => 'required|string|min:5|max:500',
        ]);

        $shop = Shop::with('user')->findOrFail($this->closureShopId);
        abort_unless($shop->closure_request_status === 'pending', 404);

        $shop->update([
            'closure_request_status' => 'rejected',
            'closure_reviewed_at' => now(),
            'closure_review_note' => $this->closureReviewNote,
        ]);

        if ($shop->user) {
            $shop->user->notify(new ShopClosureNotification(
                $shop,
                'shop_closure_rejected',
                "The closure request for {$shop->shop_name} was not approved.",
                $this->closureReviewNote
            ));
        }

        $this->closeClosureReviewModal();
        session()->flash('message', 'Closure request rejected. The owner was notified.');
    }

    //  UPDATED: Validate reason, pass it to the notification
    public function delete()
    {
        $this->validate([
            'deleteReason' => 'required|string|min:10|max:500',
        ], [
            'deleteReason.required' => 'Please provide a reason for deleting this shop.',
            'deleteReason.min' => 'Please provide at least 10 characters.',
            'deleteReason.max' => 'Reason is too long (max 500 characters).',
        ]);

        $shop = Shop::findOrFail($this->shopToDeleteId);
        $shopName = $shop->shop_name;
        $user = $shop->user;

        //  Notify owner BEFORE deleting — pass reason (wrapped)
        if ($user) {
            try {
                Notification::send($user, new ShopDeletedByAdminNotification($shop, $this->deleteReason));
            } catch (\Exception $e) {
                Log::warning('Failed to send shop deletion email', [
                    'shop_id' => $shop->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        //  Remove owner role from the user when shop is deleted
        if ($user && $user->hasRole('owner')) {
            $user->removeRole('owner');
        }

        $shop->delete();

        $this->closeDeleteModal();

        session()->flash('message', 'Shop "' . $shopName . '" deleted successfully. Owner role removed.');
    }

    public function restore(int $shopId)
    {
        $shop = Shop::withTrashed()->findOrFail($shopId);
        $shopName = $shop->shop_name;
        $user = $shop->user;

        $shop->restore();
        $shop->update([
            'closure_request_status' => null,
            'closure_request_reason' => null,
            'closure_requested_at' => null,
            'closure_reviewed_at' => null,
            'closure_review_note' => null,
        ]);

        //  Restore owner role when shop is restored
        if ($user && !$user->hasRole('owner')) {
            $user->assignRole('owner');
        }

        //  Notify owner about restoration (wrapped)
        if ($user) {
            try {
                Notification::send($user, new ShopRestoredByAdminNotification($shop));
            } catch (\Exception $e) {
                Log::warning('Failed to send shop restoration email', [
                    'shop_id' => $shop->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        session()->flash('message', 'Shop "' . $shopName . '" restored successfully. Owner role restored.');
    }

    //  UNCHANGED: forceDelete stays reason-free (Q2 = 🅱️)
    public function forceDelete(int $shopId)
    {
        $shop = Shop::withTrashed()->findOrFail($shopId);
        $shopName = $shop->shop_name;

        // Remove owner role before hard deleting
        $user = $shop->user;
        if ($user && $user->hasRole('owner')) {
            $user->removeRole('owner');
        }

        $shop->forceDelete();

        session()->flash('message', 'Shop "' . $shopName . '" permanently deleted. Owner role removed.');
    }

    public function render()
    {
        $query = Shop::with('user')
            ->when($this->showDeleted, function ($query) {
                return $query->onlyTrashed();
            });

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhereHas('user', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                    });
            });
        }

        $shops = $query->get();
        $deletedCount = Shop::onlyTrashed()->count();

        return view('livewire.admin.pages.shops.view-shops', [
            'shops' => $shops,
            'deletedCount' => $deletedCount,
        ])->layout('components.layouts.admin');
    }
}
