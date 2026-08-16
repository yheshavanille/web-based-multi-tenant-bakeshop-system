<?php

namespace App\Livewire\Employee;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Products extends Component
{
    use WithFileUploads;

    public $products = [];
    public $categories = [];
    public $branch;
    public $shop;
    public $showDeleted = false;

    // Form fields
    public $name = '';
    public $price = '';
    public $category_id = '';
    public $description = '';
    public $stock = '';
    public $image;
    public $image_url = '';
    public $editing = false;
    public $showForm = false;
    public $productId;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee record not found.');
        }

        $this->branch = Branch::find($employee->branch_id);

        if (!$this->branch) {
            abort(403, 'Branch not found. Please contact your administrator.');
        }

        $this->shop = $this->branch->shop;

        if (!$this->shop) {
            abort(403, 'Shop not found. Please contact your administrator.');
        }

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $this->shop->id)
            ->get();

        $this->loadProducts();
    }

    public function loadProducts()
    {
        $query = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->with('category');

        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        $this->products = $query->get();
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->loadProducts();
    }

    public function createNew()
    {
        $this->reset(['name', 'price', 'category_id', 'description', 'stock', 'image', 'image_url', 'productId']);
        $this->editing = false;
        $this->showForm = true;
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->branches()->where('branch_id', $this->branch->id)->exists()) {
            session()->flash('error', 'You do not have permission to edit this product.');
            return;
        }

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
        $this->image_url = $product->image_url;

        $pivot = $product->branches()->where('branch_id', $this->branch->id)->first();
        $this->stock = $pivot ? $pivot->pivot->stock : 0;

        $this->editing = true;
        $this->showForm = true;
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'stock', 'image', 'image_url', 'productId']);
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {
            $path = $this->image->store('products', 'public');
            $imagePath = Storage::url($path);
        }

        if ($this->editing) {
            $product = Product::findOrFail($this->productId);

            $product->update([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $imagePath,
            ]);

            $product->branches()->syncWithoutDetaching([
                $this->branch->id => ['stock' => $this->stock]
            ]);

            session()->flash('message', 'Product updated successfully!');
        } else {
            $product = Product::create([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $imagePath,
                'shop_id' => $this->shop->id,
            ]);

            $product->branches()->attach($this->branch->id, ['stock' => $this->stock]);

            session()->flash('message', 'Product created successfully!');
        }

        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'stock', 'image', 'image_url', 'productId']);
        $this->loadProducts();
    }

    public function delete($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->branches()->where('branch_id', $this->branch->id)->exists()) {
            session()->flash('error', 'You do not have permission to delete this product.');
            return;
        }

        $product->delete(); // Soft delete
        $this->loadProducts();
        session()->flash('message', 'Product moved to deleted records.');
    }

    public function restore($productId)
    {
        $product = Product::withTrashed()
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->findOrFail($productId);

        $product->restore();
        $this->loadProducts();
        session()->flash('message', 'Product restored successfully.');
    }

    public function updatedImage()
    {
        $this->image_url = null;
    }

    public function render()
    {
        return view('livewire.employee.products')
            ->layout('components.layouts.employee');
    }
}
