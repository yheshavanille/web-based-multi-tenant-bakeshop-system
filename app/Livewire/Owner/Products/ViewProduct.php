<?php

namespace App\Livewire\Owner\Products;

use App\Models\Branch;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductEditHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ViewProduct extends Component
{
    use WithFileUploads;

    public string $selectedCategory = 'all';
    public $selectedBranchId = null;
    public $showDeleted = false;
    public $search = '';
    public Collection $categories;
    public $branches = [];

    // Modal properties
    public $showProductModal = false;
    public $selectedProduct = null;
    public $productAnalytics = [];

    // Product Form properties
    public $showForm = false;
    public $editing = false;
    public $productId = null;
    public $name = '';
    public $price = '';
    public $category_id = '';
    public $description = '';
    public $image;
    public $image_url = '';
    public $stock = '';
    public $form_branch_id = null;

    public $originalValues = [];

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'stock' => 'required|integer|min:0',
        'form_branch_id' => 'required|exists:branches,id',
    ];

    public function mount($branch = null)
    {
        $shop = Auth::user()->shop;

        $this->selectedBranchId = $branch;
        $this->branches = $shop->branches;

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    public function updatedSearch()
    {
        // This triggers the render to update
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    public function delete(int $productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        session()->flash('message', '🗑️ Product moved to deleted records.');
    }

    public function restore(int $productId)
    {
        $product = Product::withTrashed()->findOrFail($productId);
        $product->restore();

        session()->flash('message', '✅ Product restored successfully.');
    }

    public function viewProductDetails($productId)
    {
        // ✅ Load product with ALL necessary relationships
        $this->selectedProduct = Product::with([
            'branches' => function ($query) {
                $query->withPivot('stock');
            },
            'category',
            'productReviews' => function ($query) {
                $query->with('customer')->latest();
            }
        ])->findOrFail($productId);

        // ✅ Calculate analytics
        $orderItems = OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed');
            })
            ->get();

        $this->productAnalytics = [
            'total_sold' => $orderItems->sum('quantity'),
            'total_orders' => $orderItems->groupBy('order_id')->count(),
            'total_revenue' => $orderItems->sum(function ($item) {
                return $item->quantity * $item->price;
            }),
        ];

        $this->showProductModal = true;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
        $this->productAnalytics = [];
        $this->dispatch('product-modal-closed');
    }

    // PRODUCT FORM METHODS
    public function showCreateForm()
    {
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'stock', 'form_branch_id']);
        $this->editing = false;
        $this->showForm = true;
        $this->originalValues = [];
    }

    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);

        $this->originalValues = [
            'name' => $product->name,
            'price' => (string)$product->price,
            'category_id' => (string)$product->category_id,
            'description' => (string)$product->description,
            'image_url' => (string)$product->image_url,
            'stock' => (string)($product->branches->first()?->pivot->stock ?? 0),
        ];

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = (string)$product->price;
        $this->category_id = (string)$product->category_id;
        $this->description = (string)$product->description;
        $this->image_url = (string)$product->image_url;
        $this->stock = (string)($product->branches->first()?->pivot->stock ?? 0);
        $this->form_branch_id = $product->branches->first()?->id ?? null;

        $this->editing = true;
        $this->showForm = true;
        $this->showProductModal = false;
    }

    public function cancelForm()
    {
        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'stock', 'form_branch_id']);
        $this->originalValues = [];
    }

    public function saveProduct()
    {
        $this->validate();

        $shop = Auth::user()->shop;

        $imagePath = null;
        if ($this->image) {
            $path = $this->image->store('products', 'public');
            $imagePath = Storage::url($path);
        }

        if ($this->editing) {
            $product = Product::findOrFail($this->productId);

            $fields = ['name', 'price', 'category_id', 'description', 'image_url', 'stock'];
            foreach ($fields as $field) {
                $oldValue = $this->originalValues[$field] ?? null;
                $newValue = match ($field) {
                    'stock' => $this->stock,
                    'image_url' => $imagePath ?? $product->image_url,
                    default => $this->$field,
                };

                if ((string)$oldValue !== (string)$newValue) {
                    ProductEditHistory::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'field' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ]);
                }
            }

            $product->update([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $imagePath ?? $product->image_url,
            ]);

            if ($this->form_branch_id && $this->stock !== null) {
                $branchProduct = $product->branches()->where('branch_id', $this->form_branch_id)->first();
                if ($branchProduct) {
                    $product->branches()->updateExistingPivot($this->form_branch_id, ['stock' => $this->stock]);
                }
            }

            session()->flash('message', '✅ Product updated successfully!');
        } else {
            $product = Product::create([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $imagePath,
                'shop_id' => $shop->id,
            ]);

            ProductEditHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'field' => 'created',
                'old_value' => null,
                'new_value' => 'Product created',
            ]);

            if ($this->form_branch_id) {
                $product->branches()->attach($this->form_branch_id, ['stock' => $this->stock ?? 0]);
            } else {
                $firstBranch = $shop->branches->first();
                if ($firstBranch) {
                    $product->branches()->attach($firstBranch->id, ['stock' => $this->stock ?? 0]);
                }
            }

            session()->flash('message', '✅ Product created successfully!');
        }

        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'stock', 'form_branch_id']);
        $this->originalValues = [];
    }

    public function render()
    {
        $shop = Auth::user()->shop;

        $query = $shop->products()->with(['branches' => function ($query) {
            $query->withPivot('stock');
        }, 'category']);

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->selectedBranchId) {
            $query->whereHas('branches', function ($q) {
                $q->where('branch_id', $this->selectedBranchId);
            });
        }

        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        $products = $query->get();

        // Calculate stock for each product
        foreach ($products as $product) {
            if ($this->selectedBranchId) {
                $branch = $product->branches->firstWhere('id', $this->selectedBranchId);
                $product->current_stock = $branch ? $branch->pivot->stock : 0;
            } else {
                $product->current_stock = $product->branches->sum('pivot.stock');
            }
        }

        return view('livewire.owner.products.view-product', [
            'products' => $products,
            'categories' => $this->categories,
        ])->layout('components.layouts.owner');
    }
}
