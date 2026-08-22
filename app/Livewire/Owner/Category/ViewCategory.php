<?php

namespace App\Livewire\Owner\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewCategory extends Component
{



    public function delete($id)
    {
        $category = Category::findOrFail($id);

        // ensure only owner's shop categories
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $category->delete();

        session()->flash('message', 'Category deleted successfully.');
    }
    public function render()
    {
        $categories = Auth::user()
            ->shop
            ->categories()
            ->latest()
            ->get();

        return view('livewire.owner.category.view-category', [
            'categories' => $categories,
        ])->layout('components.layouts.owner');
    }
}
