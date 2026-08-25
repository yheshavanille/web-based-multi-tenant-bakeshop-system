<?php

namespace App\Livewire\Admin;

use App\Models\SellerRegistration;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\SellerApprovedNotification;
use App\Notifications\SellerRejectedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PendingSellers extends Component
{
    public $applications;
    public $selectedApplication;
    public $showDetails = false;
    public $rejection_reason = '';
    public $rejecting = false;
    public $search = '';
    public $statusFilter = 'all';

    // ✅ CUSTOM NOTE FOR APPROVE/REJECT
    public $custom_note = '';

    // Requirements Checklist
    public $requirements = [
        'valid_id' => false,
        'business_permit' => false,
        'shop_name' => false,
        'shop_address' => false,
        'contact_number' => false,
    ];

    public function mount()
    {
        $this->loadApplications();
    }

    public function loadApplications()
    {
        $query = SellerRegistration::with('user');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('shop_name', 'like', $searchTerm)
                    ->orWhere('shop_address', 'like', $searchTerm)
                    ->orWhereHas('user', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                    });
            });
        }

        $this->applications = $query->orderBy('created_at', 'desc')->get();
    }

    public function updatedSearch()
    {
        $this->loadApplications();
    }

    public function updatedStatusFilter()
    {
        $this->loadApplications();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadApplications();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->loadApplications();
    }

    public function viewDetails($id)
    {
        $this->selectedApplication = SellerRegistration::with('user')
            ->findOrFail($id);

        // Auto-check requirements based on application data
        $this->requirements['valid_id'] = !empty($this->selectedApplication->valid_id_path);
        $this->requirements['business_permit'] = !empty($this->selectedApplication->business_permit);
        $this->requirements['shop_name'] = !empty($this->selectedApplication->shop_name);
        $this->requirements['shop_address'] = !empty($this->selectedApplication->shop_address);
        $this->requirements['contact_number'] = !empty($this->selectedApplication->contact_number);

        $this->showDetails = true;
        $this->rejecting = false;
        $this->rejection_reason = '';
        $this->custom_note = '';
    }

    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedApplication = null;
        $this->rejecting = false;
        $this->custom_note = '';
    }

    public function startReject()
    {
        $this->rejecting = true;
    }

    public function toggleRequirement($key)
    {
        $this->requirements[$key] = !$this->requirements[$key];
    }

    public function approve($id)
    {
        // Check if all requirements are checked
        $allChecked = true;
        foreach ($this->requirements as $key => $value) {
            if (!$value) {
                $allChecked = false;
                break;
            }
        }

        if (!$allChecked) {
            session()->flash('error', 'Please check all requirements before approving.');
            return;
        }

        $application = SellerRegistration::findOrFail($id);
        $user = User::find($application->user_id);

        $application->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        $user->assignRole('owner');

        Shop::create([
            'shop_name' => $application->shop_name,
            'address' => $application->shop_address,
            'description' => $application->shop_description,
            'user_id' => $user->id,
        ]);

        // ✅ SEND APPROVAL NOTIFICATION WITH CUSTOM NOTE
        Notification::send($user, new SellerApprovedNotification($application, $this->requirements, $this->custom_note));

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
        $user = User::find($application->user_id);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejection_reason,
            'reviewed_at' => now(),
        ]);

        // ✅ SEND REJECTION NOTIFICATION WITH CUSTOM NOTE
        Notification::send($user, new SellerRejectedNotification($application, $this->requirements, $this->rejection_reason, $this->custom_note));

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
