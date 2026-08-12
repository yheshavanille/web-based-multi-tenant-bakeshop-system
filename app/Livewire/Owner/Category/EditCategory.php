<?php

namespace App\Livewire\Owner\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditCategory extends Component
{
    public $categoryId;
    public $name = '';

    public function mount($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        // safety: ensure owner only edits their own category
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $this->categoryId = $category->id;
        $this->name = $category->name;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        $category = Category::findOrFail($this->categoryId);

        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $category->update([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Category updated successfully.');

        return redirect()->route('livewire.owner.category.view-category');
    }
    public function render()
    {
        return view('livewire.owner.category.edit-category');
    }
}
