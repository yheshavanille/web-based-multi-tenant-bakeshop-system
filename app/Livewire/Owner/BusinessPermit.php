<?php

namespace App\Livewire\Owner;

use App\Models\Shop;
use App\Models\User;
use App\Notifications\BusinessPermitNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusinessPermit extends Component
{
    use WithFileUploads;

    public Shop $shop;
    public $permitFile;

    public function mount(): void
    {
        $this->shop = Shop::where('user_id', auth()->id())->firstOrFail();
    }

    public function submitRenewal(): void
    {
        $shop = Shop::where('user_id', Auth::id())->firstOrFail();

        if (!$shop->canSubmitBusinessPermit()) {
            if ($shop->business_permit_review_status === 'pending_review') {
                session()->flash('error', 'Your latest permit submission is already waiting for admin review.');
            } else {
                session()->flash(
                    'error',
                    'You can submit a renewal starting ' . $shop->permit_renewal_available_date->format('M d, Y') . '.'
                );
            }

            return;
        }

        $this->validate([
            'permitFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $this->permitFile->store('business-permits', 'local');
        if (!$path) {
            throw new \RuntimeException('The business permit could not be stored.');
        }

        $previousPendingPath = $shop->pending_business_permit_path;

        $shop->update([
            'pending_business_permit_path' => $path,
            'pending_business_permit_expires_at' => null,
            'business_permit_review_status' => 'pending_review',
            'business_permit_review_note' => null,
        ]);

        if ($previousPendingPath && $previousPendingPath !== $path) {
            Storage::disk('local')->delete($previousPendingPath);
        }

        $this->shop = $shop->refresh();
        $superAdmins = User::role('super_admin')->get();
        if ($superAdmins->isNotEmpty()) {
            Notification::send(
                $superAdmins,
                new BusinessPermitNotification(
                    $this->shop,
                    'business_permit_submitted',
                    "A business permit submission for {$this->shop->shop_name} is waiting for review."
                )
            );
        }

        $this->reset('permitFile');
        session()->flash('message', 'Your permit renewal has been submitted for admin review.');
    }

    public function render()
    {
        return view('livewire.owner.business-permit')
            ->layout('components.layouts.owner');
    }
}
