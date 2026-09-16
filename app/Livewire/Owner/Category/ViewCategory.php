<?php

namespace App\Livewire\Owner\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewCategory extends Component
{
    public $search = '';

    // ✅ Soft delete toggle
    public $showDeleted = false;

    public function updatedSearch()
    {
        // This triggers the render to update
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    // ✅ Toggle between active and deleted
    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

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

    // ✅ Restore a soft-deleted category
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        // ensure only owner's shop categories
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $category->restore();

        session()->flash('message', 'Category restored successfully.');
    }

    public function render()
    {
        if ($this->showDeleted) {
            $query = Auth::user()->shop->categories()->onlyTrashed();
        } else {
            $query = Auth::user()->shop->categories();
        }

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where('name', 'like', $searchTerm);
        }

        $categories = $query->latest()->get();

        return view('livewire.owner.category.view-category', [
            'categories' => $categories,
        ])->layout('components.layouts.owner');
    }
}
