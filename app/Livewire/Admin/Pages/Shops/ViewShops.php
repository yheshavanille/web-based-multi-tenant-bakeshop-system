<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Shop;
use App\Models\User;
use Livewire\Component;

class ViewShops extends Component
{
    public $showDeleted = false;
    public $search = '';

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

    public function delete(int $shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $shopName = $shop->shop_name;
        $user = $shop->user;

        // ✅ Remove owner role from the user when shop is deleted
        if ($user && $user->hasRole('owner')) {
            $user->removeRole('owner');
        }

        $shop->delete();

        session()->flash('message', 'Shop "' . $shopName . '" deleted successfully. Owner role removed.');
    }

    public function restore(int $shopId)
    {
        $shop = Shop::withTrashed()->findOrFail($shopId);
        $shopName = $shop->shop_name;
        $user = $shop->user;

        $shop->restore();

        // ✅ Restore owner role when shop is restored
        if ($user && !$user->hasRole('owner')) {
            $user->assignRole('owner');
        }

        session()->flash('message', 'Shop "' . $shopName . '" restored successfully. Owner role restored.');
    }

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
