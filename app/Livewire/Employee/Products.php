<?php

namespace App\Livewire\Employee;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductEditHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public $search = '';

    public $name = '';
    public $price = '';
    public $category_id = '';
    public $description = '';
    public $image;
    public $image_url = '';
    public $editing = false;
    public $showForm = false;
    public $productId;

    public $originalValues = [];

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee record not found.');
        }

        if (!in_array($employee->role, ['order_manager', 'inventory_manager'])) {
            return redirect()->route('livewire.employee.dashboard')
                ->with('error', 'You do not have permission to manage products.');
        }

        $this->branch = Branch::find($employee->branch_id);

        if (!$this->branch) {
            abort(403, 'Branch not found.');
        }

        $this->shop = $this->branch->shop;

        if (!$this->shop) {
            abort(403, 'Shop not found.');
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

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        $this->products = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->loadProducts();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadProducts();
    }

    public function createNew()
    {
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'originalValues']);
        $this->editing = false;
        $this->showForm = true;
        $this->originalValues = [];
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->branches()->where('branch_id', $this->branch->id)->exists()) {
            session()->flash('error', 'You do not have permission to edit this product.');
            return;
        }

        $this->originalValues = [
            'name' => $product->name,
            'price' => (string)$product->price,
            'category_id' => (string)$product->category_id,
            'description' => (string)$product->description,
            'image_url' => (string)$product->image_url,
        ];

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = (string)$product->price;
        $this->category_id = (string)$product->category_id;
        $this->description = (string)$product->description;
        $this->image_url = (string)$product->image_url;

        $this->editing = true;
        $this->showForm = true;
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'originalValues']);
        $this->originalValues = [];
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

            // ✅ Handle image separately
            $oldImageUrl = $this->originalValues['image_url'] ?? null;
            $newImageUrl = $imagePath ?? $product->image_url;

            // Track fields
            $fields = ['name', 'price', 'category_id', 'description'];

            foreach ($fields as $field) {
                $oldValue = $this->originalValues[$field] ?? null;
                $newValue = (string)($this->$field ?? '');

                if ($oldValue !== null && $oldValue !== $newValue) {
                    ProductEditHistory::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'field' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ]);
                }
            }

            // ✅ Track image change separately
            if ($oldImageUrl !== $newImageUrl) {
                $oldLabel = $oldImageUrl ? 'Old image' : 'No image';
                $newLabel = $newImageUrl ? 'New image' : 'Removed image';

                ProductEditHistory::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'field' => 'image_url',
                    'old_value' => $oldLabel,
                    'new_value' => $newLabel,
                ]);
            }

            $product->update([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $newImageUrl,
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

            ProductEditHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'field' => 'created',
                'old_value' => null,
                'new_value' => 'Product created',
            ]);

            DB::table('branch_product')->insert([
                'branch_id' => $this->branch->id,
                'product_id' => $product->id,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            session()->flash('message', 'Product created successfully!');
        }

        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'originalValues']);
        $this->originalValues = [];
        $this->loadProducts();
    }

    public function delete($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->branches()->where('branch_id', $this->branch->id)->exists()) {
            session()->flash('error', 'You do not have permission to delete this product.');
            return;
        }

        $product->branches()->detach($this->branch->id);

        $this->loadProducts();
        session()->flash('message', 'Product removed from this branch.');
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
