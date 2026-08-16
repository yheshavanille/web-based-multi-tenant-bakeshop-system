<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $employee;
    public $shop;
    public $branch;
    public $role;
    public $orders = [];
    public $products = [];
    public $orderStats = [];
    public $totalProducts = 0;

    public function mount()
    {
        $this->employee = Auth::user()->employee;
        $this->shop = Auth::user()->shop;
        $this->branch = $this->employee->branch;
        $this->role = $this->employee->role;

        if ($this->role === 'order_manager') {
            $this->loadOrderData();
        } elseif ($this->role === 'inventory_manager') {
            $this->loadInventoryData();
        }
    }

    public function loadOrderData()
    {
        // Get orders for this branch
        $this->orders = Order::where('branch_id', $this->branch->id)
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function loadInventoryData()
    {
        $this->products = Product::where('shop_id', $this->shop->id)
            ->whereHas('branches', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->with('category')
            ->get();

        $this->totalProducts = $this->products->count();
    }

    public function render()
    {
        return view('livewire.employee.dashboard')
            ->layout('components.layouts.employee');
    }
}
