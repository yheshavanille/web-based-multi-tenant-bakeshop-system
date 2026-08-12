<?php

namespace App\Livewire\Owner\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateCategory extends Component
{
    public $name = '';

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
    ];

    public function save()
    {
        $this->validate();

        $shop = Auth::user()->shop;

        Category::create([
            'name' => $this->name,
            'shop_id' => $shop->id,
            'is_default' => false,
        ]);

        session()->flash('message', 'Category created successfully.');

        return redirect()->route('livewire.owner.category.view-category');
    }
    public function render()
    {
        return view('livewire.owner.category.create-category');
    }
}
