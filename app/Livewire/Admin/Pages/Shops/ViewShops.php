<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Shop;
use Livewire\Component;

class ViewShops extends Component
{
    public $showDeleted = false;

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    public function delete(int $shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $shop->delete();

        session()->flash('message', 'Shop deleted successfully.');
    }

    public function restore(int $shopId)
    {
        $shop = Shop::withTrashed()->findOrFail($shopId);
        $shop->restore();

        session()->flash('message', 'Shop restored successfully.');
    }

    public function forceDelete(int $shopId)
    {
        $shop = Shop::withTrashed()->findOrFail($shopId);
        $shopName = $shop->shop_name;
        $shop->forceDelete();

        session()->flash('message', 'Shop "' . $shopName . '" permanently deleted.');
    }

    public function render()
    {
        $shops = Shop::with('user')
            ->when($this->showDeleted, function ($query) {
                return $query->onlyTrashed();
            })
            ->get();

        $deletedCount = Shop::onlyTrashed()->count();

        return view('livewire.admin.pages.shops.view-shops', [
            'shops' => $shops,
            'deletedCount' => $deletedCount,
        ])->layout('components.layouts.admin');
    }
}
