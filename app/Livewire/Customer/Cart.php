<?php

namespace App\Livewire\Customer;

use App\Models\Branch;
use App\Models\Cart as CartModel;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $selectedItems = [];
    public $selectAll = false;

    // Listen for cart updates
    protected $listeners = ['cartUpdated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = CartModel::with('product.branches')
            ->where('user_id', Auth::id())
            ->get();

        // Auto-select all items if cart has items
        if ($this->cartItems->count() > 0 && empty($this->selectedItems)) {
            $this->selectedItems = $this->cartItems->pluck('id')->toArray();
            $this->selectAll = true;
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = $this->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function getSelectedTotalProperty()
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            if (in_array($item->id, $this->selectedItems)) {
                $total += $item->product->price * $item->quantity;
            }
        }
        return $total;
    }

    public function getSelectedCountProperty()
    {
        return count($this->selectedItems);
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = $this->cartItems->pluck('id')->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function updatedSelectAll()
    {
        $this->toggleSelectAll();
    }

    public function updatedSelectedItems()
    {
        // Check if all items are selected
        $this->selectAll = count($this->selectedItems) === $this->cartItems->count();
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

        $cart = CartModel::with('product')->find($cartId);

        if (!$cart) {
            return;
        }

        // Check stock availability
        $branch = Branch::find($cart->branch_id);
        if ($branch) {
            $pivot = $branch->products()->where('product_id', $cart->product_id)->first();
            $availableStock = $pivot ? $pivot->pivot->stock : 0;

            if ($quantity > $availableStock) {
                session()->flash('error', 'Not enough stock available for ' . $cart->product->name . '. Only ' . $availableStock . ' left.');
                $this->loadCart();
                return;
            }
        }

        $cart->update(['quantity' => $quantity]);
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function removeFromCart($cartId)
    {
        CartModel::where('user_id', Auth::id())
            ->where('id', $cartId)
            ->delete();

        // Remove from selected items if present
        $this->selectedItems = array_diff($this->selectedItems, [$cartId]);

        session()->flash('message', 'Item removed from cart.');
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        CartModel::where('user_id', Auth::id())->delete();
        $this->selectedItems = [];
        session()->flash('message', 'Cart cleared.');
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function checkoutSelected()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Please select at least one item to checkout.');
            return;
        }

        // Store selected cart IDs in session for checkout
        session()->put('checkout_items', $this->selectedItems);

        return redirect()->route('livewire.customer.checkout');
    }

    public function render()
    {
        return view('livewire.customer.cart')
            ->layout('components.layouts.customer');
    }
}
