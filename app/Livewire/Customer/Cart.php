<?php

namespace App\Livewire\Customer;

use App\Models\Cart as CartModel;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = CartModel::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = $this->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        $cart = CartModel::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            CartModel::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        session()->flash('message', 'Product added to cart!');
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function updateQuantity($cartId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($cartId);
            return;
        }

        $cart = CartModel::where('user_id', Auth::id())
            ->where('id', $cartId)
            ->first();

        if ($cart) {
            $cart->update(['quantity' => $quantity]);
            $this->loadCart();
        }
    }

    public function removeFromCart($cartId)
    {
        CartModel::where('user_id', Auth::id())
            ->where('id', $cartId)
            ->delete();

        session()->flash('message', 'Item removed from cart.');
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        CartModel::where('user_id', Auth::id())->delete();
        session()->flash('message', 'Cart cleared.');
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.customer.cart')
            ->layout('components.layouts.customer');
    }
}
