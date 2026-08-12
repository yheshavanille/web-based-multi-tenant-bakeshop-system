<?php

namespace App\Livewire\Customer;

use App\Models\Shop;
use Livewire\Component;

class BrowseShops extends Component
{
    public function render()
    {
        $shops = Shop::with('user')->get();
        return view('livewire.customer.browse-shops', compact('shops'))
            ->layout('components.layouts.customer');
    }
}
