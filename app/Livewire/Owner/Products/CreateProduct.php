<?php

namespace App\Livewire\Owner\Products;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Rules\UniqueProductName;
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
    public array $branch_stocks = [];

    public Collection $categories;
    public $branches;

    public string $image_url = '';
    public $image = null;
    public string $description = '';

    public $discount_type = 'none';
    public $discount_value = 0;
    public $discount_start;
    public $discount_end;

    public function updatedName()
    {
        $this->validateOnly('name');
    }

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

        foreach ($this->branches as $branch) {
            $this->branch_stocks[$branch->id] = 0;
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 40 characters.',
            'name.regex' => 'Product name must contain at least one letter and can only use letters, numbers, spaces, and common punctuation ( . , - ( ) & \' / ).',
            'price.required' => 'Price is required.',
            'category_id.required' => 'Category is required.',
            'selectedBranches.required' => 'Please select at least one branch.',
            'selectedBranches.min' => 'Please select at least one branch.',
            'image_url.url' => 'Image URL must be valid.',
            'discount_value.min' => 'Discount value must be greater than 0.',

            'price.max' => 'Price cannot exceed ₱999,999.99.',
            'price.min' => 'Price must be at least 1.',
            'discount_value.max' => 'Discount value is too large. For percentage, use 0–100. For fixed amount, use 0–999,999.99.',
            'branch_stocks.*.max' => 'Branch stock cannot exceed 999,999.',
            'branch_stocks.*.numeric' => 'Branch stock must be a number.',
            'branch_stocks.*.min' => 'Branch stock cannot be negative.',

            'image.image' => 'The file must be a valid image (JPG, PNG, GIF, or WebP).',
            'image.mimes' => 'The image must be a JPG, PNG, GIF, or WebP file. PDFs and other files are not allowed.',
            'image.max' => 'The image must not be larger than 2MB.',
        ];
    }

    public function rules()
    {
        $shop = Auth::user()?->shop;
        $discountMax = $this->discount_type === 'percentage' ? 100 : 999999.99;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:40',
                'regex:/^(?=.*\p{L})[\p{L}\p{N}\s\-\.\,\(\)\&\'\/]+$/u',
                $shop ? new UniqueProductName($shop->id) : 'string',
            ],
            'price' => 'required|numeric|min:1|max:999999.99',
            'category_id' => 'required|exists:categories,id',
            'selectedBranches' => 'required|array|min:1',
            'selectedBranches.*' => 'exists:branches,id',
            'branch_stocks' => 'array',
            'branch_stocks.*' => 'nullable|numeric|min:0|max:999999',
            'image_url' => 'nullable|url',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'description' => 'nullable|string|max:5000',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_value' => 'required_if:discount_type,percentage,fixed|nullable|numeric|min:0|max:' . $discountMax,
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
            'name' => trim($this->name),
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
            $stock = (int) ($this->branch_stocks[$branchId] ?? 0);
            $product->branches()->attach($branchId, ['stock' => $stock]);
        }

        session()->flash('message', ' Product created successfully!');

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
