<?php

namespace App\Livewire\Admin\Pages\Shops;

use App\Models\Shop;
use Livewire\Component;

class ViewShops extends Component
{

    public function delete(int $shopId)
    {
        Shop::findOrFail($shopId)->delete();

        session()->flash('message', 'Shop deleted successfully.');
    }

    public function render()
    {

        return view('livewire.admin.pages.shops.view-shops', [
            'shops' => Shop::with('user')->get(),
        ]);
    }
}
