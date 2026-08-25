<?php

namespace App\Livewire\Customer;

use App\Models\Shop;
use Livewire\Component;

class BrowseShops extends Component
{
    public $search = '';
    public $shops;

    public function mount()
    {
        $this->loadShops();
    }

    public function loadShops()
    {
        $query = Shop::with('user');

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        $this->shops = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadShops();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadShops();
    }

    public function render()
    {
        return view('livewire.customer.browse-shops', ['shops' => $this->shops])
            ->layout('components.layouts.customer');
    }
}
