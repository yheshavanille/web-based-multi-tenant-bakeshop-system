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
    public $selectedBranches = [];
    public $stock_per_branch = null;
    public $image_url;
    public $image;
    public $description;
    public $categories;
    public $branches;

    public $discount_type = 'none';
    public $discount_value = 0;
    public $discount_start;
    public $discount_end;

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
        $product = Product::with('branches')->findOrFail($productId);
        $user = Auth::user();

        if (!$user || !$user->shop || $product->shop_id !== $user->shop->id) {
            abort(403);
        }

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->image_url = $product->image_url;
        $this->description = $product->description;

        $this->discount_type = $product->discount_type ?? 'none';
        $this->discount_value = $product->discount_value ?? 0;
        $this->discount_start = $product->discount_start ? $product->discount_start->format('Y-m-d\TH:i') : null;
        $this->discount_end = $product->discount_end ? $product->discount_end->format('Y-m-d\TH:i') : null;

        $this->selectedBranches = $product->branches->pluck('id')->toArray();

        if ($product->branches->isNotEmpty()) {
            $this->stock_per_branch = $product->branches->first()->pivot->stock ?? null;
        }

        $shop = $user->shop;

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();

        $this->branches = Branch::where('shop_id', $shop->id)
            ->orderBy('name')
            ->get();
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'price.required' => 'Price is required.',
            'category_id.required' => 'Category is required.',
            'selectedBranches.required' => 'Please select at least one branch.',
            'discount_value.min' => 'Discount value must be greater than 0.',
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'selectedBranches' => 'required|array|min:1',
            'selectedBranches.*' => 'exists:branches,id',
            'image_url' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_value' => 'required_if:discount_type,percentage,fixed|nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after:discount_start',
        ];
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
            'image_url' => $imagePath,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_type !== 'none' ? $this->discount_value : 0,
            'discount_start' => $this->discount_start,
            'discount_end' => $this->discount_end,
        ]);

        $syncData = [];
        foreach ($this->selectedBranches as $branchId) {
            $syncData[$branchId] = ['stock' => $this->stock_per_branch ?? 0];
        }
        $product->branches()->sync($syncData);

        session()->flash('message', '✅ Product updated successfully.');

        return redirect()->route('livewire.owner.products.view-product');
    }

    public function render()
    {
        return view('livewire.owner.products.edit-product', [
            'categories' => $this->categories,
            'branches' => $this->branches,
        ]);
    }
}
