<?php

namespace App\Livewire\Admin\Pages\Role;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ViewRole extends Component
{

    public $permissions;

    public function mount()
    {

        $this->permissions = Permission::all();
    }

    public function getRolesProperty()
    {
        return Role::with('permissions')->get();
    }
    public function delete($id)
    {
        Role::findOrFail($id)->delete();

        session()->flash('success', 'Role deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.pages.role.view-role');
    }
}
