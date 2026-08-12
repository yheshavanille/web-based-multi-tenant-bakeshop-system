<?php

namespace App\Livewire\Admin\Pages\Users;

use App\Models\User;
use Livewire\Component;

class ViewUser extends Component
{

    public function delete($userId)
    {
        User::findOrFail($userId)->delete();

        session()->flash('message', 'User deleted successfully.');
    }

    #[Layout('admin.layouts.app')]
    public function render()
    {
        return view('livewire.admin.pages.users.view-user', [
            'users' => User::with('roles:id,name')
                ->select('id', 'name', 'email', 'created_at')
                ->latest()
                ->get()
        ]);
    }
}
