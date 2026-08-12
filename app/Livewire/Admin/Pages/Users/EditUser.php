<?php

namespace App\Livewire\Admin\Pages\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Shop;

class EditUser extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $roles;
    public $selectedRole;

    public function mount($userId)
    {
        $this->userId = $userId;

        $user = User::findOrFail($userId);

        $this->name = $user->name;
        $this->email = $user->email;

        // get current role (Spatie way)
        $this->selectedRole = $user->roles->first()?->id;

        $this->roles = Role::select('id', 'name')->get();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|unique:users,name,' . $this->userId,
            'email' => 'required|email:rfc,dns|unique:users,email,' . $this->userId,
            'password' => 'nullable|min:6|confirmed',
            'selectedRole' => 'required|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The user name is required.',
            'name.unique' => 'The user name is already taken.',
            'email.required' => 'The email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'The email is already taken.',
            'password.confirmed' => 'Password confirmation does not match.',
            'selectedRole.required' => 'Please select a role.',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $user->update([
            'name' => trim($this->name),
            'email' => $this->email,
            'password' => $this->password
                ? Hash::make($this->password)
                : $user->password,
        ]);

        $role = Role::findById($this->selectedRole);

        if ($role) {
            $user->syncRoles([$role->name]);

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $shop = Shop::where('user_id', $user->id)->first();

            if ($role->name === 'owner') {
                if (!$shop) {
                    Shop::create([
                        'shop_name' => $user->name,
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                if ($shop) {
                    $shop->delete();
                }
            }
        }

        session()->flash('success', 'User updated successfully.');

        return redirect()->route('livewire.admin.pages.users.view-user');
    }
    public function render()
    {
        return view('livewire.admin.pages.users.edit-user');
    }
}
