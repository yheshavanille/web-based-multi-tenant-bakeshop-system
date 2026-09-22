<?php

namespace App\Livewire\Employee;

use App\Models\Product;
use App\Models\StockHistory;
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
    public $stockUpdates = [];
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
            $stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
            $this->stockUpdates[$product->id] = $stock;

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

    // ✅ Notify inventory managers + owner about low/out-of-stock
    private function notifyLowOrOutOfStock(Product $product, int $newStock): void
    {
        // Low stock threshold
        $threshold = 5;

        $isOutOfStock = $newStock <= 0;
        $isLowStock = $newStock > 0 && $newStock <= $threshold;

        if (!$isOutOfStock && !$isLowStock) {
            return;
        }

        // Recipients: inventory managers of this shop + owner
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

        // Deduplicate (in case owner is somehow also an inventory manager)
        $recipients = $recipients->unique('id');

        if ($recipients->count() === 0) {
            return;
        }

        if ($isOutOfStock) {
            Notification::send($recipients, new OutOfStockNotification($product, $this->branch));
        } else {
            Notification::send($recipients, new LowStockNotification($product, $this->branch, $newStock));
        }
    }

    public function updateStock($productId)
    {
        $product = Product::findOrFail($productId);
        $newStock = $this->stockUpdates[$productId] ?? 0;
        $note = $this->notes[$productId] ?? '';
        $productName = $product->name;

        if ($newStock < 0) {
            session()->flash('error', 'Stock cannot be negative.');
            return;
        }

        $oldStock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;

        $product->branches()->syncWithoutDetaching([
            $this->branch->id => ['stock' => $newStock]
        ]);

        StockHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'branch_id' => $this->branch->id,
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

        // ✅ NEW: Notify inventory managers + owner if stock is low or out
        $this->notifyLowOrOutOfStock($product, $newStock);

        $this->notes[$productId] = '';

        $this->loadProducts();

        session()->flash('message', "✅ Stock updated for {$productName}!");
    }

    public function render()
    {
        return view('livewire.employee.manage-stock')
            ->layout('components.layouts.employee');
    }
}
