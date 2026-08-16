<?php

namespace App\Livewire\Owner;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $branches = [];
    public $totalProducts = 0;
    public $employeesCount = 0;

    public function mount()
    {
        $shop = Auth::user()->shop;

        $this->branches = Branch::where('shop_id', $shop->id)
            ->withCount('products')
            ->get();

        $this->totalProducts = Product::where('shop_id', $shop->id)->count();
        $this->employeesCount = Employee::where('shop_id', $shop->id)->count();
    }

    public function render()
    {
        return view('livewire.owner.dashboard')
            ->layout('components.layouts.owner');
    }
}
