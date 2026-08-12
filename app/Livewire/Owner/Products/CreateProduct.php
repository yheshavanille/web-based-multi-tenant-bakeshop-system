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
    public string $branch_id = '';
    public Collection $categories;
    public $branches;

    public string $image_url = '';
    public $image = null;
    public string $description = '';

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
            'branch_id.required' => 'Branch is required.',
            'image_url.url' => 'Image URL must be valid.',
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
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

        Product::create([
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'branch_id' => $this->branch_id,
            'image_url' => $imagePath,
            'description' => $this->description,
            'shop_id' => $shop->id,
        ]);

        session()->flash('message', 'Product created successfully.');

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
