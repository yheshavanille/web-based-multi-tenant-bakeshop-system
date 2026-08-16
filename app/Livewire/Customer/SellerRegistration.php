<?php

namespace App\Livewire\Customer;

use App\Models\SellerRegistration as SellerRegistrationModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class SellerRegistration extends Component
{
    use WithFileUploads;

    public $shop_name = '';
    public $shop_address = '';
    public $contact_number = '';
    public $shop_description = '';
    public $business_permit;
    public $step = 1;

    protected $rules = [
        'shop_name' => 'required|string|min:3|max:255',
        'shop_address' => 'required|string|min:5',
        'contact_number' => 'required|string|max:20',
        'shop_description' => 'nullable|string|max:500',
        'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];

    public function mount()
    {
        // Check if user already has a pending application
        $pending = SellerRegistrationModel::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            session()->flash('error', 'You already have a pending application. Please wait for approval.');
            return redirect()->route('livewire.customer.dashboard');
        }

        // Check if user is already a seller
        if (auth()->user()->hasRole('owner')) {
            session()->flash('error', 'You are already a registered seller.');
            return redirect()->route('livewire.owner.dashboard');
        }
    }

    public function nextStep()
    {
        $this->validate([
            'shop_name' => 'required|string|min:3|max:255',
            'shop_address' => 'required|string|min:5',
            'contact_number' => 'required|string|max:20',
            'shop_description' => 'nullable|string|max:500',
        ]);

        $this->step = 2;
    }

    public function previousStep()
    {
        $this->step = 1;
    }

    public function submit()
    {
        // Double-check for existing application
        $existing = SellerRegistrationModel::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            session()->flash('error', 'You already have a pending application.');
            return redirect()->route('livewire.customer.dashboard');
        }

        if (auth()->user()->hasRole('owner')) {
            session()->flash('error', 'You are already a registered seller.');
            return redirect()->route('livewire.owner.dashboard');
        }

        $this->validate();

        $permitPath = null;
        if ($this->business_permit) {
            $permitPath = $this->business_permit->store('business_permits', 'public');
        }

        SellerRegistrationModel::create([
            'user_id' => Auth::id(),
            'shop_name' => $this->shop_name,
            'shop_address' => $this->shop_address,
            'contact_number' => $this->contact_number,
            'shop_description' => $this->shop_description,
            'business_permit' => $permitPath,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        session()->flash('success', 'Application submitted! You will be notified once approved.');
        return redirect()->route('livewire.customer.dashboard');
    }

    public function render()
    {
        return view('livewire.customer.seller-registration')
            ->layout('components.layouts.customer');
    }
}
