<?php

namespace App\Livewire\Customer;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $featuredShops;
    public $products = [];   // ✅ NEW
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
        // ✅ Shop search (existing, unchanged)
        $query = Shop::with('user')->latest();

        // ✅ Product search (new)
        $productQuery = Product::with(['shop', 'category', 'branches']);

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });

            // ✅ Product matches: name or description
            $productQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            })
                ->whereHas('shop', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->whereHas('branches', function ($q) {
                    $q->where('branch_product.stock', '>', 0)
                        ->where('branches.is_active', true);
                });

            $this->products = $productQuery->limit(8)->get();
        } else {
            $this->products = collect();
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
        $this->products = collect();
        $this->loadShops();
    }

    public function updateUser($data)
    {
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->phone = $data['phone'];
    }

    public function hasActiveShop()
    {
        return Shop::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();
    }

    public function canApplyAsSeller()
    {
        $hasOwnerRole = auth()->user()->hasRole('owner');
        $hasActiveShop = $this->hasActiveShop();

        if ($hasOwnerRole && !$hasActiveShop) {
            auth()->user()->removeRole('owner');
            return true;
        }

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
