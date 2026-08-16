<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = Order::with(['items.product', 'items.branch', 'shop', 'branch'])
            ->where('customer_id', Auth::id())
            ->findOrFail($order);
    }

    public function render()
    {
        return view('livewire.customer.order-confirmation')
            ->layout('components.layouts.customer');
    }
}
