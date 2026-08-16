<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Orders extends Component
{
    public $orders = [];
    public $statusCounts = [];
    public $showDetails = [];

    public function mount()
    {
        $this->loadOrders();
        $this->loadStatusCounts();
    }

    public function loadOrders()
    {
        $this->orders = Order::where('customer_id', Auth::id())
            ->with(['items.product', 'shop', 'branch'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function loadStatusCounts()
    {
        $this->statusCounts = [
            'pending' => Order::where('customer_id', Auth::id())->where('status', 'pending')->count(),
            'preparing' => Order::where('customer_id', Auth::id())->where('status', 'preparing')->count(),
            'ready_for_pickup' => Order::where('customer_id', Auth::id())->where('status', 'ready_for_pickup')->count(),
            'completed' => Order::where('customer_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled' => Order::where('customer_id', Auth::id())->where('status', 'cancelled')->count(),
        ];
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('customer_id', Auth::id())
            ->where('id', $orderId)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            session()->flash('error', 'This order cannot be cancelled.');
            return;
        }

        $order->update(['status' => 'cancelled']);

        $this->loadOrders();
        $this->loadStatusCounts();
        session()->flash('message', 'Order cancelled successfully.');
    }

    public function render()
    {
        return view('livewire.customer.orders')
            ->layout('components.layouts.customer');
    }
}
