<?php

namespace App\Livewire\Owner\Products;

use App\Models\Branch;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductEditHistory;
use App\Rules\UniqueProductName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public $showProductModal = false;
    public $selectedProduct = null;
    public $productAnalytics = [];

    public $showForm = false;
    public $editing = false;
    public $productId = null;
    public $name = '';
    public $price = '';
    public $category_id = '';
    public $description = '';
    public $image;
    public $image_url = '';

    public array $selectedBranches = [];
    public array $branch_stocks = [];

    public $discount_type = 'none';
    public $discount_value = 0;

    public $originalValues = [];

    protected $messages = [
        'selectedBranches.required' => 'Please select at least one branch.',
        'selectedBranches.min' => 'Please select at least one branch.',
    ];

    /**
     * ✅ Dynamic rules — the name rule depends on whether we're editing
     *    (must skip the current product) or creating.
     */
    protected function rules()
    {
        $shop = Auth::user()->shop;
        $ignoreId = $this->editing ? $this->productId : null;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                new UniqueProductName($shop->id, $ignoreId),
            ],
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'selectedBranches' => 'required|array|min:1',
            'selectedBranches.*' => 'exists:branches,id',
            'branch_stocks' => 'array',
            'branch_stocks.*' => 'nullable|integer|min:0',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
        ];
    }

    public function mount($branch = null)
    {
        $shop = Auth::user()->shop;

        $this->selectedBranchId = $branch;
        $this->branches = $shop->branches;

        $this->categories = Category::whereNull('shop_id')
            ->orWhere('shop_id', $shop->id)
            ->get();

        foreach ($this->branches as $b) {
            $this->branch_stocks[$b->id] = 0;
        }
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    public function updatedSearch()
    {
        //
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    public function updatedDiscountType()
    {
        if ($this->discount_type === 'none') {
            $this->discount_value = 0;
        }
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
        $this->selectedProduct = Product::with([
            'branches' => function ($query) {
                $query->withPivot('stock');
            },
            'category',
            'productReviews' => function ($query) {
                $query->with('customer')->latest();
            }
        ])->findOrFail($productId);

        $branchIds = $this->selectedProduct->branches->pluck('id')->toArray();

        if (!empty($branchIds)) {
            $orderItems = OrderItem::where('product_id', $productId)
                ->whereHas('order', function ($q) use ($branchIds) {
                    $q->where('status', 'completed')
                        ->whereIn('branch_id', $branchIds);
                })
                ->get();
        } else {
            $orderItems = OrderItem::where('product_id', $productId)
                ->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                })
                ->get();
        }

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

    public function showCreateForm()
    {
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'discount_type', 'discount_value']);
        $this->selectedBranches = [];
        $this->branch_stocks = [];

        foreach ($this->branches as $b) {
            $this->branch_stocks[$b->id] = 0;
        }

        $this->editing = false;
        $this->showForm = true;
        $this->originalValues = [];
        $this->discount_type = 'none';
        $this->discount_value = 0;
    }

    public function editProduct($productId)
    {
        $product = Product::with('branches')->findOrFail($productId);

        $this->selectedBranches = [];
        $this->branch_stocks = [];

        foreach ($this->branches as $b) {
            $this->branch_stocks[$b->id] = 0;
        }

        foreach ($product->branches as $branch) {
            $this->selectedBranches[] = $branch->id;
            $this->branch_stocks[$branch->id] = (int) ($branch->pivot->stock ?? 0);
        }

        $this->originalValues = [
            'name' => $product->name,
            'price' => (string) $product->price,
            'category_id' => (string) $product->category_id,
            'description' => (string) $product->description,
            'image_url' => (string) $product->image_url,
            'discount_type' => $product->discount_type ?? 'none',
            'discount_value' => (string) ($product->discount_value ?? 0),
            'branch_stocks' => $product->branches->mapWithKeys(function ($b) {
                return [$b->id => (int) $b->pivot->stock];
            })->toArray(),
            'branch_names' => $product->branches->mapWithKeys(function ($b) {
                return [$b->id => $b->name];
            })->toArray(),
        ];

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = (string) $product->price;
        $this->category_id = (string) $product->category_id;
        $this->description = (string) $product->description;
        $this->image_url = (string) $product->image_url;
        $this->discount_type = $product->discount_type ?? 'none';
        $this->discount_value = $product->discount_value ?? 0;

        $this->editing = true;
        $this->showForm = true;
        $this->showProductModal = false;
    }

    public function cancelForm()
    {
        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'discount_type', 'discount_value']);
        $this->selectedBranches = [];
        $this->branch_stocks = [];
        foreach ($this->branches as $b) {
            $this->branch_stocks[$b->id] = 0;
        }
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

            $oldImageUrl = $this->originalValues['image_url'] ?? null;
            $newImageUrl = $imagePath ?? $product->image_url;

            $fields = ['name', 'price', 'category_id', 'description'];
            foreach ($fields as $field) {
                $oldValue = $this->originalValues[$field] ?? null;
                $newValue = (string) ($this->$field ?? '');

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

            if ($oldImageUrl !== $newImageUrl) {
                ProductEditHistory::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'field' => 'image_url',
                    'old_value' => $oldImageUrl ? 'Old image' : 'No image',
                    'new_value' => $newImageUrl ? 'New image' : 'Removed image',
                ]);
            }

            $oldBranchStocks = $this->originalValues['branch_stocks'] ?? [];
            $oldBranchNames = $this->originalValues['branch_names'] ?? [];

            foreach ($this->selectedBranches as $branchId) {
                if (!array_key_exists($branchId, $oldBranchStocks)) {
                    $branch = Branch::find($branchId);
                    ProductEditHistory::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'field' => 'branch',
                        'old_value' => 'Not assigned',
                        'new_value' => ($branch?->name ?? 'Unknown') . ' (stock: ' . ($this->branch_stocks[$branchId] ?? 0) . ')',
                    ]);
                }
            }

            foreach ($oldBranchStocks as $oldBranchId => $oldStock) {
                if (!in_array($oldBranchId, $this->selectedBranches)) {
                    ProductEditHistory::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'field' => 'branch',
                        'old_value' => ($oldBranchNames[$oldBranchId] ?? 'Unknown') . ' (stock: ' . $oldStock . ')',
                        'new_value' => 'Removed',
                    ]);
                }
            }

            foreach ($oldBranchStocks as $oldBranchId => $oldStock) {
                if (in_array($oldBranchId, $this->selectedBranches)) {
                    $newStock = (int) ($this->branch_stocks[$oldBranchId] ?? 0);
                    if ((int) $oldStock !== $newStock) {
                        ProductEditHistory::create([
                            'product_id' => $product->id,
                            'user_id' => Auth::id(),
                            'field' => 'stock',
                            'old_value' => ($oldBranchNames[$oldBranchId] ?? 'Branch') . ': ' . $oldStock,
                            'new_value' => ($oldBranchNames[$oldBranchId] ?? 'Branch') . ': ' . $newStock,
                        ]);
                    }
                }
            }

            $oldDiscountType = $this->originalValues['discount_type'] ?? 'none';
            $newDiscountType = $this->discount_type ?? 'none';
            $oldDiscountValue = $this->originalValues['discount_value'] ?? 0;
            $newDiscountValue = $this->discount_value ?? 0;

            if ($oldDiscountType !== $newDiscountType || (string) $oldDiscountValue !== (string) $newDiscountValue) {
                ProductEditHistory::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'field' => 'discount',
                    'old_value' => $oldDiscountType !== 'none' ? $oldDiscountType . ' (' . $oldDiscountValue . ')' : 'No discount',
                    'new_value' => $newDiscountType !== 'none' ? $newDiscountType . ' (' . $newDiscountValue . ')' : 'No discount',
                ]);
            }

            $product->update([
                'name' => trim($this->name),
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $newImageUrl,
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_type !== 'none' ? $this->discount_value : 0,
            ]);

            $syncData = [];
            foreach ($this->selectedBranches as $branchId) {
                $syncData[$branchId] = ['stock' => (int) ($this->branch_stocks[$branchId] ?? 0)];
            }
            $product->branches()->sync($syncData);

            session()->flash('message', '✅ Product updated successfully!');
        } else {
            $product = Product::create([
                'name' => trim($this->name),
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image_url' => $imagePath,
                'shop_id' => $shop->id,
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_type !== 'none' ? $this->discount_value : 0,
            ]);

            ProductEditHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'field' => 'created',
                'old_value' => null,
                'new_value' => 'Product created',
            ]);

            foreach ($this->selectedBranches as $branchId) {
                $product->branches()->attach($branchId, [
                    'stock' => (int) ($this->branch_stocks[$branchId] ?? 0),
                ]);
            }

            session()->flash('message', '✅ Product created successfully!');
        }

        $this->showForm = false;
        $this->editing = false;
        $this->reset(['name', 'price', 'category_id', 'description', 'image', 'image_url', 'productId', 'discount_type', 'discount_value']);
        $this->selectedBranches = [];
        $this->branch_stocks = [];
        foreach ($this->branches as $b) {
            $this->branch_stocks[$b->id] = 0;
        }
        $this->originalValues = [];
    }

    public function render()
    {
        $shop = Auth::user()->shop;

        $query = $shop->products()->with(['branches' => function ($query) {
            $query->withPivot('stock');
        }, 'category'])
            ->withCount('productReviews');

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
