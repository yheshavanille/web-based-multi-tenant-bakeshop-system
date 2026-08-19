<?php

namespace App\Livewire\Owner\Branches;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BranchOrders extends Component
{
    public $branch;
    public $orders = [];
    public $showOrderDetails = false;
    public $selectedOrder = null;

    public function mount($branchId)
    {
        $this->branch = Branch::with('shop')->findOrFail($branchId);

        // Verify the branch belongs to the logged-in owner
        if ($this->branch->shop->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::where('branch_id', $this->branch->id)
            ->where('status', 'completed')
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function viewOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['customer', 'items.product', 'branch'])
            ->findOrFail($orderId);
        $this->showOrderDetails = true;
    }

    public function closeOrderDetails()
    {
        $this->showOrderDetails = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        return view('livewire.owner.branches.branch-orders')
            ->layout('components.layouts.owner');
    }
}
