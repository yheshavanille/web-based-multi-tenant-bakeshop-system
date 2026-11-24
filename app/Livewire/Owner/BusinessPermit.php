<?php

namespace App\Livewire\Owner;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusinessPermit extends Component
{
    use WithFileUploads;

    public Shop $shop;
    public $permitFile;
    public string $permitExpiryDate = '';

    public function mount(): void
    {
        $this->shop = Shop::where('user_id', auth()->id())->firstOrFail();
        $this->permitExpiryDate = $this->shop->pending_business_permit_expires_at?->format('Y-m-d') ?? '';
    }

    public function submitRenewal(): void
    {
        $this->validate([
            'permitFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'permitExpiryDate' => 'required|date|after_or_equal:today',
        ]);

        $shop = Shop::where('user_id', Auth::id())->firstOrFail();
        $path = $this->permitFile->store('business-permits', 'local');
        if (!$path) {
            throw new \RuntimeException('The business permit could not be stored.');
        }

        $previousPendingPath = $shop->pending_business_permit_path;

        $shop->update([
            'pending_business_permit_path' => $path,
            'pending_business_permit_expires_at' => $this->permitExpiryDate,
            'business_permit_review_status' => 'pending_review',
            'business_permit_review_note' => null,
        ]);

        if ($previousPendingPath && $previousPendingPath !== $path) {
            Storage::disk('local')->delete($previousPendingPath);
        }

        $this->shop = $shop->refresh();
        $this->reset('permitFile');
        session()->flash('message', 'Your permit renewal has been submitted for admin review.');
    }

    public function render()
    {
        return view('livewire.owner.business-permit')
            ->layout('components.layouts.owner');
    }
}
