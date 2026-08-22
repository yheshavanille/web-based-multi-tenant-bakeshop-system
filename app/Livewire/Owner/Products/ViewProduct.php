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
    public Collection $categories;
    public $branches = [];

    // Modal properties
    public $showProductModal = false;
    public $selectedProduct = null;
    public $productAnalytics = [];

    // Discount properties
    public $editMode = false;
    public $discount_type = 'none';
    public $discount_value = 0;
    public $discount_start = null;
    public $discount_end = null;

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
    public $form_discount_type = 'none';
    public $form_discount_value = 0;
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
        'form_discount_type' => 'in:none,percentage,fixed',
        'form_discount_value' => 'numeric|min:0',
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
        $this->selectedProduct = Product::with('branches', 'category')
            ->findOrFail($productId);

        $this->discount_type = $this->selectedProduct->discount_type ?? 'none';
        $this->discount_value = $this->selectedProduct->discount_value ?? 0;
        $this->discount_start = $this->selectedProduct->discount_start;
        $this->discount_end = $this->selectedProduct->discount_end;
        $this->editMode = false;

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
        $this->editMode = false;
        $this->discount_type = 'none';
        $this->discount_value = 0;
        $this->discount_start = null;
        $this->discount_end = null;
        $this->dispatch('product-modal-closed');
    }

    public function enableEditMode()
    {
        $this->editMode = true;
    }

    public function saveDiscount()
    {
        if (!$this->selectedProduct) {
            return;
        }

        $oldDiscountType = $this->selectedProduct->discount_type ?? 'none';
        $oldDiscountValue = $this->selectedProduct->discount_value ?? 0;

        if ($this->discount_type === 'none' || $this->discount_value <= 0) {
            $this->selectedProduct->update([
                'discount_type' => 'none',
                'discount_value' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ]);

            if ($oldDiscountType !== 'none' || $oldDiscountValue > 0) {
                ProductEditHistory::create([
                    'product_id' => $this->selectedProduct->id,
                    'user_id' => Auth::id(),
                    'field' => 'discount_type',
                    'old_value' => $oldDiscountType === 'percentage' ? $oldDiscountValue . '%' : '₱' . number_format($oldDiscountValue, 2),
                    'new_value' => 'Removed',
                ]);
            }

            session()->flash('message', '✅ Discount removed successfully.');
        } else {
            $this->selectedProduct->update([
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_value,
                'discount_start' => $this->discount_start,
                'discount_end' => $this->discount_end,
            ]);

            $discountLabel = $this->discount_type === 'percentage'
                ? $this->discount_value . '%'
                : '₱' . number_format($this->discount_value, 2);

            $oldLabel = $oldDiscountType === 'percentage' ? $oldDiscountValue . '%' : '₱' . number_format($oldDiscountValue, 2);
            ProductEditHistory::create([
                'product_id' => $this->selectedProduct->id,
                'user_id' => Auth::id(),
                'field' => 'discount_type',
                'old_value' => $oldDiscountType === 'none' || $oldDiscountValue <= 0 ? 'No Discount' : $oldLabel,
                'new_value' => $discountLabel,
            ]);

            session()->flash('message', '✅ Discount set to ' . $discountLabel . ' successfully!');
        }

        $this->selectedProduct = Product::with('branches', 'category')->findOrFail($this->selectedProduct->id);
        $this->editMode = false;
    }

    // PRODUCT FORM METHODS
    public function showCreateForm()
    {
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'stock', 'form_discount_type', 'form_discount_value', 'form_branch_id']);
        $this->editing = false;
        $this->showForm = true;
        $this->originalValues = [];
    }

    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);

        // ✅ STORE ORIGINAL VALUES
        $this->originalValues = [
            'name' => $product->name,
            'price' => (string)$product->price,
            'category_id' => (string)$product->category_id,
            'description' => (string)$product->description,
            'image_url' => (string)$product->image_url,
            'stock' => (string)($product->branches->first()?->pivot->stock ?? 0),
            'discount_type' => $product->discount_type ?? 'none',
            'discount_value' => (string)($product->discount_value ?? 0),
        ];

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = (string)$product->price;
        $this->category_id = (string)$product->category_id;
        $this->description = (string)$product->description;
        $this->image_url = (string)$product->image_url;
        $this->stock = (string)($product->branches->first()?->pivot->stock ?? 0);
        $this->form_discount_type = $product->discount_type ?? 'none';
        $this->form_discount_value = (string)($product->discount_value ?? 0);
        $this->form_branch_id = $product->branches->first()?->id ?? null;

        $this->editing = true;
        $this->showForm = true;
        $this->showProductModal = false;
    }

    public function cancelForm()
    {
        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'stock', 'form_discount_type', 'form_discount_value', 'form_branch_id']);
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

            // ✅ LOG CHANGED FIELDS (ONLY THE ONES THAT ACTUALLY CHANGED)
            $fields = ['name', 'price', 'category_id', 'description', 'image_url', 'stock'];
            foreach ($fields as $field) {
                $oldValue = $this->originalValues[$field] ?? null;
                $newValue = match ($field) {
                    'stock' => $this->stock,
                    'image_url' => $imagePath ?? $product->image_url,
                    default => $this->$field,
                };

                // ✅ ONLY LOG IF VALUE ACTUALLY CHANGED
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
                'discount_type' => $this->form_discount_type,
                'discount_value' => $this->form_discount_value,
            ]);

            // ✅ Update stock for the selected branch
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
                'discount_type' => $this->form_discount_type,
                'discount_value' => $this->form_discount_value,
            ]);

            // ✅ LOG PRODUCT CREATION
            ProductEditHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'field' => 'created',
                'old_value' => null,
                'new_value' => 'Product created',
            ]);

            // ✅ Attach to selected branch with stock
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
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'stock', 'form_discount_type', 'form_discount_value', 'form_branch_id']);
        $this->originalValues = [];
    }

    public function render()
    {
        $shop = Auth::user()->shop;

        $query = $shop->products()->with('branches');

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

        return view('livewire.owner.products.view-product', [
            'products' => $query->get(),
            'categories' => $this->categories,
        ])->layout('components.layouts.owner');
    }
}
