<?php

namespace App\Livewire\Customer;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $featuredShops;
    public $user;

    protected $listeners = ['profile-updated' => 'updateUser'];

    public function mount()
    {
        $this->user = Auth::user();
        $this->featuredShops = Shop::with('user')
            ->latest()
            ->limit(3)
            ->get();
    }

    public function updateUser($data)
    {
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->phone = $data['phone'];
    }

    public function render()
    {
        return view('livewire.customer.dashboard')
            ->layout('components.layouts.customer');
    }
}
