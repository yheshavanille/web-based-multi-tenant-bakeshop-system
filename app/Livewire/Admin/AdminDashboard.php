<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\SellerRegistration;
use App\Models\Shop;
use App\Models\User;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalShops;
    public $totalProducts;
    public $pendingSellers;
    public $recentApplications;

    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalShops = Shop::count();
        $this->totalProducts = Product::count();
        $this->pendingSellers = SellerRegistration::where('status', 'pending')->count();
        $this->recentApplications = SellerRegistration::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('components.layouts.admin');
    }
}
