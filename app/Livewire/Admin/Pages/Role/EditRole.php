<?php

namespace App\Livewire\Admin\Pages\Role;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRole extends Component
{
    public $role;
    public $name;
    public $permissions = [];
    public $selectedPermissions = [];

    public function mount($role)
    {
        $this->role = Role::findOrFail($role);

        $this->name = $this->role->name;

        $this->permissions = Permission::all();

        $this->selectedPermissions = $this->role
            ->permissions
            ->pluck('name')
            ->toArray();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string',
            'selectedPermissions' => 'array',
        ]);

        // update role name
        $this->role->update([
            'name' => $this->name,
        ]);

        // sync permissions
        $this->role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Role updated successfully.');

        return redirect()->route('livewire.admin.pages.role.view-role');
    }

    public function render()
    {
        return view('livewire.admin.pages.role.edit-role');
    }
}
