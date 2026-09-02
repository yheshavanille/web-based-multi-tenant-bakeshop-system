<?php

namespace App\Livewire\Customer;

use App\Models\SellerRegistration;
use App\Models\User;
use App\Notifications\NewSellerRegistrationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class StartSelling extends Component
{
    use WithFileUploads;

    public $hasPendingApplication = false;
    public $isAlreadySeller = false;
    public $shop_name = '';
    public $shop_address = '';
    public $shop_description = '';
    public $contact_number = '';
    public $business_permit = '';
    public $valid_id = '';

    public function mount()
    {
        $this->hasPendingApplication = SellerRegistration::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        $this->isAlreadySeller = auth()->user()->hasRole('owner');
    }

    public function submitApplication()
    {
        $this->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'valid_id' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Store files
        $businessPermitPath = $this->business_permit->store('seller_documents', 'public');
        $validIdPath = $this->valid_id->store('seller_documents', 'public');

        // Create registration
        $registration = SellerRegistration::create([
            'user_id' => Auth::id(),
            'shop_name' => $this->shop_name,
            'shop_address' => $this->shop_address,
            'shop_description' => $this->shop_description,
            'contact_number' => $this->contact_number,
            'business_permit' => $businessPermitPath,
            'valid_id_path' => $validIdPath,
            'status' => 'pending',
        ]);

        // ✅ SEND NOTIFICATION TO ALL SUPER ADMINS
        $superAdmins = User::where('role', 'super_admin')->get();

        if ($superAdmins->count() > 0) {
            Notification::send($superAdmins, new NewSellerRegistrationNotification($registration));
        }

        session()->flash('message', 'Your seller application has been submitted! Please wait for approval.');
        return redirect()->route('livewire.customer.dashboard');
    }

    public function render()
    {
        return view('livewire.customer.start-selling')
            ->layout('components.layouts.customer');
    }
}
