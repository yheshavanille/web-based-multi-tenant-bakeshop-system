<?php

namespace App\Livewire\Owner\Shop;

use App\Models\Shop;
use App\Models\User;
use App\Notifications\ShopClosureNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditShop extends Component
{
    use WithFileUploads;

    public $shop;
    public $shop_name;
    public $shop_image;
    public $image;
    public $address;
    public $description;

    // ✅ NEW: Store Hours
    public $opening_time;
    public $closing_time;

    public bool $showClosureRequestModal = false;
    public string $closureReason = '';

    public function updatedImage()
    {
        if ($this->image) {
            $this->shop_image = null;
        }
    }

    public function removeShopImageUrl()
    {
        $this->shop_image = null;
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function mount()
    {
        $this->shop = Shop::where('user_id', auth()->id())->first();

        if (!$this->shop) {
            abort(404, 'Shop not found');
        }

        $this->shop_name = $this->shop->shop_name;
        $this->shop_image = $this->shop->shop_image;
        $this->address = $this->shop->address;
        $this->description = $this->shop->description;

        // ✅ Load Store Hours (fallback to defaults if null)
        $this->opening_time = $this->shop->opening_time ?? '08:00';
        $this->closing_time = $this->shop->closing_time ?? '22:00';
    }

    public function save()
    {
        $this->validate([
            'shop_name' => 'required|string|min:3|max:255',
            'image' => 'nullable|image|max:2048',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            // ✅ Validate Store Hours
            'opening_time' => 'required',
            'closing_time' => 'required|after:opening_time',
        ], [
            'closing_time.after' => 'The closing time must be after the opening time.',
        ]);

        $imagePath = $this->shop_image;

        if ($this->image) {
            // Delete old image if exists
            if ($this->shop->shop_image) {
                $oldPath = str_replace('/storage/', '', $this->shop->shop_image);
                \Storage::disk('public')->delete($oldPath);
            }

            $path = $this->image->store('shops', 'public');
            $imagePath = '/storage/' . $path;
        }

        $this->shop->update([
            'shop_name' => $this->shop_name,
            'shop_image' => $imagePath,
            'address' => $this->address,
            'description' => $this->description,
            'opening_time' => $this->opening_time, // ✅ Save
            'closing_time' => $this->closing_time, // ✅ Save
        ]);

        session()->flash('message', 'Shop updated successfully!');

        return redirect()->route('livewire.owner.dashboard');
    }

    public function openClosureRequestModal(): void
    {
        if ($this->shop->closure_request_status === 'pending') {
            session()->flash('error', 'Your shop closure request is already waiting for admin review.');
            return;
        }

        if ($this->shop->hasUnresolvedOrders()) {
            session()->flash('error', 'Resolve all pending, preparing, ready for pickup, partially completed, and unpaid or partially paid orders before requesting shop closure.');
            return;
        }

        $this->closureReason = '';
        $this->showClosureRequestModal = true;
        $this->resetErrorBag();
    }

    public function closeClosureRequestModal(): void
    {
        $this->showClosureRequestModal = false;
        $this->closureReason = '';
        $this->resetErrorBag();
    }

    public function requestShopClosure(): void
    {
        $this->validate([
            'closureReason' => 'required|string|min:10|max:500',
        ], [
            'closureReason.required' => 'Please tell us why you are requesting shop closure.',
            'closureReason.min' => 'Please provide at least 10 characters.',
            'closureReason.max' => 'Reason is too long (max 500 characters).',
        ]);

        $shop = Shop::where('user_id', Auth::id())->firstOrFail();

        if ($shop->closure_request_status === 'pending') {
            session()->flash('error', 'Your shop closure request is already waiting for admin review.');
            $this->closeClosureRequestModal();
            return;
        }

        if ($shop->hasUnresolvedOrders()) {
            session()->flash('error', 'Resolve all pending, preparing, ready for pickup, partially completed, and unpaid or partially paid orders before requesting shop closure.');
            return;
        }

        $shop->update([
            'closure_request_status' => 'pending',
            'closure_request_reason' => $this->closureReason,
            'closure_requested_at' => now(),
            'closure_reviewed_at' => null,
            'closure_review_note' => null,
        ]);

        $this->shop = $shop->refresh();
        $superAdmins = User::role('super_admin')->get();
        if ($superAdmins->isNotEmpty()) {
            Notification::send(
                $superAdmins,
                new ShopClosureNotification(
                    $shop,
                    'shop_closure_requested',
                    "The owner of {$shop->shop_name} has requested shop closure."
                )
            );
        }

        $this->closeClosureRequestModal();
        session()->flash('message', 'Your shop closure request has been submitted for admin review.');
    }

    public function render()
    {
        return view('livewire.owner.shop.edit-shop')
            ->layout('components.layouts.owner');
    }
}
