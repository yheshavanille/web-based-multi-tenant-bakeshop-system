<?php

namespace App\Livewire\Owner\Products;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;

    public string $name = '';
    public float $price = 0;
    public string $category_id = '';
    public array $selectedBranches = [];
    public $stock_per_branch = null;
    public Collection $categories;
    public $branches;

    public string $image_url = '';
    public $image = null;
    public string $description = '';

    public $discount_type = 'none';
    public $discount_value = 0;
    public $discount_start;
    public $discount_end;

    public function updatedImage()
    {
        if ($this->image) {
            $this->image_url = '';
        }
    }

    public function updatedImageUrl()
    {
        if ($this->image_url) {
            $this->image = null;
        }
    }

    public function getHasImageFileProperty()
    {
        return !is_null($this->image);
    }

    public function getHasImageUrlProperty()
    {
        return !empty($this->image_url);
    }

    public function mount()
    {
        $shop = Auth::user()?->shop;

        if (!$shop) {
            abort(403);
        }

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
            'image_url.url' => 'Image URL must be valid.',
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
            'image_url' => 'nullable|url',
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

        $shop = Auth::user()?->shop;

        if (!$shop) {
            abort(403);
        }

        $imagePath = null;

        if ($this->image) {
            $path = $this->image->store('products', 'public');
            $imagePath = Storage::url($path);
        } elseif ($this->image_url) {
            $imagePath = $this->image_url;
        }

        $product = Product::create([
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'image_url' => $imagePath,
            'description' => $this->description,
            'shop_id' => $shop->id,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_type !== 'none' ? $this->discount_value : 0,
            'discount_start' => $this->discount_start,
            'discount_end' => $this->discount_end,
        ]);

        foreach ($this->selectedBranches as $branchId) {
            $product->branches()->attach($branchId, ['stock' => $this->stock_per_branch ?? 0]);
        }

        session()->flash('message', '✅ Product created successfully!');

        return redirect()->route('livewire.owner.products.view-product');
    }

    public function render()
    {
        return view('livewire.owner.products.create-product', [
            'categories' => $this->categories,
            'branches' => $this->branches,
        ]);
    }
}
