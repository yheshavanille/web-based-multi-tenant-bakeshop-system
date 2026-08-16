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

    public function updatedImage()
    {
        if ($this->image) {
            $this->shop_image = null;
        }
    }

    public function updatedShopImage()
    {
        if ($this->shop_image) {
            $this->image = null;
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
            'shop_name' => 'required|string|min:3',
            'shop_image' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $imagePath = $this->shop_image;

        if ($this->image) {
            $path = $this->image->store('shops', 'public');
            $imagePath = '/storage/' . $path;
        }

        $this->shop->update([
            'shop_name' => $this->shop_name,
            'shop_image' => $imagePath,
            'address' => $this->address,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Shop updated successfully.');

        return redirect()->route('livewire.owner.dashboard');
    }

    public function deleteShop()
    {
        $shop = Auth::user()->shop;
        $shopName = $shop->shop_name;
        $shop->delete();

        session()->flash('message', 'Shop "' . $shopName . '" has been deleted.');
        return redirect()->route('livewire.customer.dashboard');
    }

    public function render()
    {
        return view('livewire.owner.shop.edit-shop');
    }
}
