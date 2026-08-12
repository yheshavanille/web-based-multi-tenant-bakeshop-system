<?php

namespace App\Livewire\Owner\Branches;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageBranches extends Component
{
    public $branchId;
    public $name = '';
    public $address = '';
    public $contact_number = '';
    public $is_active = true;
    public $branches = [];
    public $editing = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'address' => 'required|string|min:5',
            'contact_number' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ];
    }

    public function mount(): void
    {
        $this->resetForm();
        $this->loadBranches();
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    private function getShop()
    {
        $user = Auth::user();

        if (!$user || !$user->shop) {
            abort(403);
        }

        return $user->shop;
    }

    public function loadBranches(): void
    {
        $shop = $this->getShop();
        $this->branches = Branch::where('shop_id', $shop->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function resetForm(): void
    {
        $this->branchId = null;
        $this->name = '';
        $this->address = '';
        $this->contact_number = '';
        $this->is_active = true;
        $this->editing = false;
    }

    public function createNewBranch(): void
    {
        $this->resetForm();
    }

    public function editBranch(int $branchId): void
    {
        $shop = $this->getShop();

        $branch = Branch::where('shop_id', $shop->id)->findOrFail($branchId);

        $this->branchId = $branch->id;
        $this->name = $branch->name;
        $this->address = $branch->address;
        $this->contact_number = $branch->contact_number;
        $this->is_active = $branch->is_active;
        $this->editing = true;
    }

    public function saveBranch(): void
    {
        $this->validate();

        $shop = $this->getShop();

        $data = [
            'shop_id' => $shop->id,
            'name' => $this->name,
            'address' => $this->address,
            'contact_number' => $this->contact_number,
            'is_active' => $this->is_active,
        ];

        if ($this->editing && $this->branchId) {
            $branch = Branch::where('shop_id', $shop->id)->findOrFail($this->branchId);
            $branch->update($data);
            session()->flash('message', 'Branch updated successfully.');
        } else {
            Branch::create($data);
            session()->flash('message', 'Branch created successfully.');
        }

        $this->resetForm();
        $this->loadBranches();
    }

    public function toggleStatus(int $branchId): void
    {
        $shop = $this->getShop();
        $branch = Branch::where('shop_id', $shop->id)->findOrFail($branchId);

        $branch->update([
            'is_active' => !$branch->is_active,
        ]);

        session()->flash('message', 'Branch status updated.');
        $this->loadBranches();
    }

    public function deleteBranch(int $branchId): void
    {
        $shop = $this->getShop();
        $branch = Branch::where('shop_id', $shop->id)->findOrFail($branchId);

        $branch->delete();

        session()->flash('message', 'Branch deleted successfully.');
        $this->resetForm();
        $this->loadBranches();
    }


    public function render()
    {
        return view('livewire.owner.branches.manage-branches');
    }
}
