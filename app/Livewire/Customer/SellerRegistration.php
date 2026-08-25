<?php

namespace App\Livewire\Customer;

use App\Models\SellerRegistration as SellerRegistrationModel;
use App\Models\Shop;
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
    public $valid_id;
    public $step = 1;

    protected $rules = [
        'shop_name' => 'required|string|min:3|max:255',
        'shop_address' => 'required|string|min:5',
        'contact_number' => 'required|string|regex:/^09\d{9}$/|size:11',
        'shop_description' => 'nullable|string|max:500',
        'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'valid_id' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];

    protected $messages = [
        'contact_number.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789).',
        'contact_number.size' => 'Phone number must be exactly 11 digits.',
        'contact_number.required' => 'Contact number is required.',
    ];

    public function mount()
    {
        $pending = SellerRegistrationModel::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            session()->flash('error', 'You already have a pending application. Please wait for approval.');
            return redirect()->route('livewire.customer.dashboard');
        }

        // ✅ Check if user has an ACTIVE shop (not soft-deleted)
        $hasActiveShop = Shop::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();

        if ($hasActiveShop) {
            session()->flash('error', 'You are already a registered seller with an active shop.');
            return redirect()->route('livewire.owner.dashboard');
        }

        // If user has owner role but no active shop, remove the role so they can reapply
        if (auth()->user()->hasRole('owner') && !$hasActiveShop) {
            auth()->user()->removeRole('owner');
        }
    }

    public function nextStep()
    {
        $this->validate([
            'shop_name' => 'required|string|min:3|max:255',
            'shop_address' => 'required|string|min:5',
            'contact_number' => 'required|string|regex:/^09\d{9}$/|size:11',
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
        $existing = SellerRegistrationModel::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            session()->flash('error', 'You already have a pending application.');
            return redirect()->route('livewire.customer.dashboard');
        }

        // ✅ Check if user has an ACTIVE shop (not soft-deleted)
        $hasActiveShop = Shop::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();

        if ($hasActiveShop) {
            session()->flash('error', 'You are already a registered seller with an active shop.');
            return redirect()->route('livewire.owner.dashboard');
        }

        // If user has owner role but no active shop, remove the role
        if (auth()->user()->hasRole('owner') && !$hasActiveShop) {
            auth()->user()->removeRole('owner');
        }

        $this->validate();

        $permitPath = null;
        if ($this->business_permit) {
            $permitPath = $this->business_permit->store('business_permits', 'public');
        }

        $validIdPath = null;
        if ($this->valid_id) {
            $validIdPath = $this->valid_id->store('valid_ids', 'public');
        }

        SellerRegistrationModel::create([
            'user_id' => Auth::id(),
            'shop_name' => $this->shop_name,
            'shop_address' => $this->shop_address,
            'contact_number' => $this->contact_number,
            'shop_description' => $this->shop_description,
            'business_permit' => $permitPath,
            'valid_id_path' => $validIdPath,
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
