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

    // ✅ NEW: Delete reason modal state
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
    }

    public function save()
    {
        $this->validate([
            'shop_name' => 'required|string|min:3|max:255',
            'image' => 'nullable|image|max:2048',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
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
        ]);

        session()->flash('message', 'Shop updated successfully!');

        return redirect()->route('livewire.owner.dashboard');
    }

    // ✅ NEW: Open the delete confirmation modal
    public function openDeleteModal()
    {
        $this->deleteReason = '';
        $this->showDeleteModal = true;
        $this->resetErrorBag();
    }

    // ✅ NEW: Close the modal
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteReason = '';
        $this->resetErrorBag();
    }

    // ✅ UPDATED: Validate reason, pass it to the notification
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

        // ✅ Notify all super admins BEFORE deleting — pass reason
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
