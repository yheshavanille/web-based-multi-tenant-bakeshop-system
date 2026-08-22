<?php

namespace App\Livewire\Employee;

use App\Models\Product;
use App\Models\StockHistory;
use App\Models\ProductEditHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageStock extends Component
{
    public $products = [];
    public $stockUpdates = [];
    public $notes = [];
    public $branch;
    public $shop;

    public function mount()
    {
        $employee = Auth::user()->employee;
        $this->branch = $employee->branch;
        $this->shop = $employee->shop;

        // ✅ Only inventory managers can access this page
        if ($employee->role !== 'inventory_manager') {
            return redirect()->route('livewire.employee.dashboard')
                ->with('error', 'You do not have permission to manage stock.');
        }

        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->with('category')
            ->get();

        foreach ($this->products as $product) {
            $stock = $product->branches->firstWhere('id', $this->branch->id)?->pivot->stock ?? 0;
            $this->stockUpdates[$product->id] = $stock;

            if (!isset($this->notes[$product->id])) {
                $this->notes[$product->id] = '';
            }
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

        // ✅ Update stock
        $product->branches()->syncWithoutDetaching([
            $this->branch->id => ['stock' => $newStock]
        ]);

        // ✅ Log to StockHistory
        StockHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'branch_id' => $this->branch->id,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => $note,
        ]);

        // ✅ LOG TO PRODUCT EDIT HISTORY (for Recent Product Updates)
        ProductEditHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'field' => 'stock',
            'old_value' => (string)$oldStock,
            'new_value' => (string)$newStock,
        ]);

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
