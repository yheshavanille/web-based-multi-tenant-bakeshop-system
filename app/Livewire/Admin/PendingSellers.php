<?php

namespace App\Livewire\Admin;

use App\Models\SellerRegistration;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PendingSellers extends Component
{
    public $applications;
    public $selectedApplication;
    public $showDetails = false;
    public $rejection_reason = '';
    public $rejecting = false;

    public function mount()
    {
        $this->loadApplications();
    }

    public function loadApplications()
    {
        $this->applications = SellerRegistration::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function viewDetails($id)
    {
        $this->selectedApplication = SellerRegistration::with('user')->findOrFail($id);
        $this->showDetails = true;
        $this->rejecting = false;
        $this->rejection_reason = '';
    }

    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedApplication = null;
        $this->rejecting = false;
    }

    public function startReject()
    {
        $this->rejecting = true;
    }

    public function approve($id)
    {
        $application = SellerRegistration::findOrFail($id);

        // Update application status
        $application->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        // Get the user
        $user = User::find($application->user_id);

        // Assign owner role
        $user->assignRole('owner');

        // Create shop
        Shop::create([
            'shop_name' => $application->shop_name,
            'address' => $application->shop_address,
            'description' => $application->shop_description,
            'user_id' => $user->id,
        ]);

        $this->loadApplications();
        $this->closeDetails();

        session()->flash('message', 'Seller approved successfully! Shop created.');
    }

    public function reject($id)
    {
        $this->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $application = SellerRegistration::findOrFail($id);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejection_reason,
            'reviewed_at' => now(),
        ]);

        $this->loadApplications();
        $this->closeDetails();

        session()->flash('message', 'Seller application rejected.');
    }

    public function render()
    {
        return view('livewire.admin.pending-sellers')
            ->layout('components.layouts.admin');
    }
}
