<?php

namespace App\Livewire\Owner\Shop;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
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

    // ✅ Delete reason modal state
    public $showDeleteModal = false;
    public $deleteReason = '';

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

    public function openDeleteModal()
    {
        $this->deleteReason = '';
        $this->showDeleteModal = true;
        $this->resetErrorBag();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteReason = '';
        $this->resetErrorBag();
    }

    public function deleteShop()
    {
        $this->validate([
            'deleteReason' => 'required|string|min:10|max:500',
        ], [
            'deleteReason.required' => 'Please tell us why you are deleting your shop.',
            'deleteReason.min' => 'Please provide at least 10 characters.',
            'deleteReason.max' => 'Reason is too long (max 500 characters).',
        ]);

        $shop = Auth::user()->shop;
        $shopName = $shop->shop_name;

        $superAdmins = \App\Models\User::role('super_admin')->get();
        if ($superAdmins->count() > 0) {
            \Illuminate\Support\Facades\Notification::send(
                $superAdmins,
                new \App\Notifications\ShopDeletedByOwnerNotification($shop, $this->deleteReason)
            );
        }

        $shop->delete();

        $this->showDeleteModal = false;
        $this->deleteReason = '';

        session()->flash('message', 'Shop "' . $shopName . '" has been deleted.');
        return redirect()->route('livewire.customer.dashboard');
    }

    public function render()
    {
        return view('livewire.owner.shop.edit-shop')
            ->layout('components.layouts.owner');
    }
}
