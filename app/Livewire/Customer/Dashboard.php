<?php

namespace App\Livewire\Customer;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $featuredShops;
    public $user;
    public $search = '';

    protected $listeners = ['profile-updated' => 'updateUser'];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadShops();
    }

    public function loadShops()
    {
        $query = Shop::with('user')->latest();

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        $this->featuredShops = $query->limit(3)->get();
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

    public function updateUser($data)
    {
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->phone = $data['phone'];
    }

    /**
     * Check if user has an active shop (not soft-deleted)
     */
    public function hasActiveShop()
    {
        return Shop::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * Check if user can apply as a seller
     */
    public function canApplyAsSeller()
    {
        $hasOwnerRole = auth()->user()->hasRole('owner');
        $hasActiveShop = $this->hasActiveShop();

        // If user has owner role but no active shop, remove the role
        if ($hasOwnerRole && !$hasActiveShop) {
            auth()->user()->removeRole('owner');
            return true;
        }

        // Can apply if: no owner role OR (has owner role but no active shop)
        return !$hasOwnerRole || !$hasActiveShop;
    }

    public function render()
    {
        return view('livewire.customer.dashboard', [
            'canApplyAsSeller' => $this->canApplyAsSeller(),
            'hasActiveShop' => $this->hasActiveShop(),
        ])->layout('components.layouts.customer');
    }
}
