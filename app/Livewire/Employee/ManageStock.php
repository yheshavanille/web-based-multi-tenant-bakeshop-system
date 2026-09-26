<?php

namespace App\Livewire\Employee;

use App\Models\Product;
use App\Models\InventoryHistory;
use App\Models\ProductEditHistory;
use App\Models\Employee;
use App\Notifications\LowStockNotification;
use App\Notifications\OutOfStockNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ManageStock extends Component
{
    public $products = [];
    public $stockUpdates = [];    // ✅ now holds DELTAS (user enters +2 / -3)
    public $notes = [];
    public $branch;
    public $shop;
    public $search = '';

    public function mount()
    {
        $employee = Auth::user()->employee;
        $this->branch = $employee->branch;
        $this->shop = $employee->shop;

        if ($employee->role !== 'inventory_manager') {
            return redirect()->route('livewire.employee.dashboard')
                ->with('error', 'You do not have permission to manage stock.');
        }

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

        $this->products = $query->get();

        foreach ($this->products as $product) {
            // ✅ Start empty — user enters the delta
            $this->stockUpdates[$product->id] = '';

            if (!isset($this->notes[$product->id])) {
                $this->notes[$product->id] = '';
            }
        }
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }
    public function clearSearch()
    {
        $this->search = '';
        $this->loadProducts();
    }

    private function notifyLowOrOutOfStock(Product $product, int $newStock): void
    {
        $threshold = 5;
        $isOutOfStock = $newStock <= 0;
        $isLowStock = $newStock > 0 && $newStock <= $threshold;

        if (!$isOutOfStock && !$isLowStock) {
            return;
        }

        $inventoryManagers = Employee::where('shop_id', $this->shop->id)
            ->where('role', 'inventory_manager')
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();

        $owner = $this->shop->user ?? null;

        $recipients = $inventoryManagers;
        if ($owner) {
            $recipients = $recipients->push($owner);
        }

        $recipients = $recipients->unique('id');
        if ($recipients->count() === 0) return;

        if ($isOutOfStock) {
            Notification::send($recipients, new OutOfStockNotification($product, $this->branch));
        } else {
            Notification::send($recipients, new LowStockNotification($product, $this->branch, $newStock));
        }
    }

    public function updateStock($productId)
    {
        $product = Product::findOrFail($productId);
        $deltaRaw = $this->stockUpdates[$productId] ?? '';
        $note = $this->notes[$productId] ?? '';
        $productName = $product->name;

        // ✅ Parse delta — allow "", "+2", "2", "-3"
        $delta = ($deltaRaw === '' || $deltaRaw === null) ? 0 : (int) $deltaRaw;

        if ($delta === 0) {
            session()->flash('error', 'No change made to stock.');
            return;
        }

        if (trim($note) === '') {
            session()->flash('error', 'Please provide a reason for changing the stock.');
            return;
        }

        $oldStock = (int) ($product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0);
        $newStock = $oldStock + $delta;

        if ($newStock < 0) {
            session()->flash('error', 'Stock cannot go below 0. Current stock: ' . $oldStock);
            return;
        }

        $type = $delta > 0 ? 'stock_in' : 'stock_out';

        $product->branches()->syncWithoutDetaching([
            $this->branch->id => ['stock' => $newStock]
        ]);

        InventoryHistory::create([
            'product_id' => $product->id,
            'branch_id' => $this->branch->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantity' => $delta,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => $note,
        ]);

        ProductEditHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'field' => 'stock',
            'old_value' => (string) $oldStock,
            'new_value' => (string) $newStock,
        ]);

        $this->notifyLowOrOutOfStock($product, $newStock);

        $this->notes[$productId] = '';

        $this->loadProducts();

        session()->flash('message', "Stock updated for {$productName}!");
    }

    public function render()
    {
        return view('livewire.employee.manage-stock')
            ->layout('components.layouts.employee');
    }
}
