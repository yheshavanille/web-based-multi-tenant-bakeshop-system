<?php

namespace App\Livewire\Owner;

use App\Models\ProductEditHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductHistory extends Component
{
    use WithPagination;

    public $tab = 'product-updates'; // 'product-updates' | 'product-edits'
    public $search = '';
    public $fieldFilter = 'all';

    protected $queryString = [
        'tab' => ['except' => 'product-updates'],
    ];

    public function mount()
    {
        $tab = request()->get('tab');
        if (in_array($tab, ['product-updates', 'product-edits'])) {
            $this->tab = $tab;
        }
    }

    public function setTab($tab)
    {
        if (!in_array($tab, ['product-updates', 'product-edits'])) {
            return;
        }
        $this->tab = $tab;
        $this->search = '';
        $this->fieldFilter = 'all';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFieldFilter()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Build the base query for the current tab.
     */
    private function buildQuery()
    {
        $shop = Auth::user()->shop;

        $query = ProductEditHistory::whereHas('product', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with(['product.branches', 'user']);

        // Tab filter
        if ($this->tab === 'product-updates') {
            // Product Updates tab: show recent 10 (matches dashboard's "Recent Product Updates")
            // but with pagination — first page shows 10, subsequent pages show the next batches
            // (This effectively shows the same data as "Product Edits" but paginated at 10 per page)
        } elseif ($this->tab === 'product-edits') {
            // Product Edits tab: full history (paginated at 20 per page)
        }

        // Search
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        // Field filter
        if ($this->fieldFilter !== 'all') {
            $query->where('field', $this->fieldFilter);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the field options for the dropdown.
     */
    public function getFieldOptionsProperty()
    {
        return [
            'all' => 'All Fields',
            'created' => 'Created',
            'name' => 'Name',
            'price' => 'Price',
            'category_id' => 'Category',
            'description' => 'Description',
            'image_url' => 'Image',
            'branch' => 'Branch',
            'stock' => 'Stock',
            'discount' => 'Discount',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
        ];
    }

    /**
     * Clean old-format values for display.
     */
    public function cleanValue($field, $value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $value = (string) $value;

        if ($field === 'stock' && preg_match('/:\s*(-?\d+)\s*$/', $value, $m)) {
            return $m[1];
        }

        if ($field === 'branch' && preg_match('/\(stock:\s*(-?\d+)\)/', $value, $m)) {
            return 'Stock: ' . $m[1];
        }

        return $value;
    }

    public function render()
    {
        $perPage = $this->tab === 'product-updates' ? 10 : 20;
        $histories = $this->buildQuery()->paginate($perPage);

        return view('livewire.owner.product-history', [
            'histories' => $histories,
            'fieldOptions' => $this->fieldOptions,
        ])->layout('components.layouts.owner');
    }
}
