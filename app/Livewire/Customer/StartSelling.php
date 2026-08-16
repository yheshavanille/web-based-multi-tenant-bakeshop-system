<?php

namespace App\Livewire\Customer;

use App\Models\SellerRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StartSelling extends Component
{
    public $hasPendingApplication = false;
    public $isAlreadySeller = false;

    public function mount()
    {
        $this->hasPendingApplication = SellerRegistration::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        $this->isAlreadySeller = auth()->user()->hasRole('owner');
    }

    public function render()
    {
        return view('livewire.customer.start-selling')
            ->layout('components.layouts.customer');
    }
}
