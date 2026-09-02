<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public $orders = [];
    public $totalAmount = 0;
    public $totalSubtotal = 0;
    public $totalTax = 0;
    public $shopName = '';

    public function mount($order = null)
    {
        $orderIds = [];

        // If $order is a comma-separated string, explode it
        if ($order && strpos($order, ',') !== false) {
            $orderIds = explode(',', $order);
        } elseif ($order) {
            $orderIds = [$order];
        }

        // If still empty, get the latest orders for this customer
        if (empty($orderIds)) {
            $latestOrders = Order::where('customer_id', Auth::id())
                ->latest()
                ->limit(5)
                ->get();
            $orderIds = $latestOrders->pluck('id')->toArray();
        }

        // If still empty, redirect back
        if (empty($orderIds)) {
            session()->flash('error', 'No orders found.');
            return redirect()->route('livewire.customer.orders');
        }

        // Load all orders
        $this->orders = Order::with(['items.product', 'items.branch', 'shop', 'branch'])
            ->where('customer_id', Auth::id())
            ->whereIn('id', $orderIds)
            ->get();

        // If no orders found, fallback to latest order
        if ($this->orders->isEmpty()) {
            $lastOrder = Order::where('customer_id', Auth::id())
                ->latest()
                ->first();

            if ($lastOrder) {
                $this->orders = collect([$lastOrder->load(['items.product', 'items.branch', 'shop', 'branch'])]);
            }
        }

        // If still no orders, redirect back
        if ($this->orders->isEmpty()) {
            session()->flash('error', 'No orders found.');
            return redirect()->route('livewire.customer.orders');
        }

        // Calculate totals across all orders
        foreach ($this->orders as $order) {
            $this->totalSubtotal += $order->subtotal ?? $order->total_amount;
            $this->totalTax += $order->tax_amount ?? 0;
            $this->totalAmount += $order->total_amount;

            if (!$this->shopName) {
                $this->shopName = $order->shop->shop_name;
            }
        }
    }

    public function render()
    {
        return view('livewire.customer.order-confirmation')
            ->layout('components.layouts.customer');
    }
}
