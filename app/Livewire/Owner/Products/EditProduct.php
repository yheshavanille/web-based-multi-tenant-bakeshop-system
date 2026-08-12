<?php

namespace App\Livewire\Owner\Products;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use WithFileUploads;

    public $productId;
    public $name;
    public $price;
    public $category_id;
    public $branch_id;
    public $image_url;
    public $image;
    public $description;
    public $categories;
    public $branches;

    public function updatedImage()
    {
        $this->image_url = null;
    }

    public function updatedImageUrl()
    {
        $this->image = null;
    }

    public function mount($productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        if (!$user || !$user->shop || $product->shop_id !== $user->shop->id) {
            abort(403);
        }

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->branch_id = $product->branch_id;
        $this->image_url = $product->image_url;
        $this->description = $product->description;

        $shop = $user->shop;

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();

        $this->branches = Branch::where('shop_id', $shop->id)
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user();

        if (!$user || !$user->shop) {
            abort(403);
        }

        $product = Product::findOrFail($this->productId);

        if ($product->shop_id !== $user->shop->id) {
            abort(403);
        }

        $imagePath = $this->image_url;

        if ($this->image) {
            $path = $this->image->store('products', 'public');
            $imagePath = Storage::url($path);
        }

        $product->update([
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'branch_id' => $this->branch_id,
            'image_url' => $imagePath,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Product updated successfully.');

        return redirect()->route('livewire.owner.products.view-product');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'image_url' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ];
    }


    public function render()
    {
        return view('livewire.owner.products.edit-product', [
            'categories' => $this->categories,
            'branches' => $this->branches,
        ]);
    }
}
