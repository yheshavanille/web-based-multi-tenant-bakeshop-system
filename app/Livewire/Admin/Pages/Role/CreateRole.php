<?php

namespace App\Livewire\Admin\Pages\Role;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    public $name;
    public $permissions = [];
    public $selectedPermissions = [];

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name',
            'selectedPermissions' => 'array',
        ];
    }

    public function save()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
        ]);

        if (!empty($this->selectedPermissions)) {
            $role->syncPermissions($this->selectedPermissions);
        }

        session()->flash('success', 'Role created successfully.');

        return redirect()->route('livewire.admin.pages.role.view-role');
    }
    public function render()
    {
        return view('livewire.admin.pages.role.create-role');
    }
}
