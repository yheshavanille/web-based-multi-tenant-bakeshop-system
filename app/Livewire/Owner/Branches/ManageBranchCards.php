<?php

namespace App\Livewire\Owner\Branches;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageBranchCards extends Component
{
    public $branches = [];

    public function mount()
    {
        $shop = Auth::user()->shop;
        $this->branches = Branch::where('shop_id', $shop->id)
            ->withCount(['products', 'employees'])
            ->get();
    }

    public function viewBranchProducts($branchId)
    {
        return redirect()->route('livewire.owner.products.view-product', ['branch' => $branchId]);
    }

    public function render()
    {
        return view('livewire.owner.branches.manage-branch-cards')
            ->layout('components.layouts.owner');
    }
}
