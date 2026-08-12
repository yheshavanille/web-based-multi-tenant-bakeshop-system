<?php

namespace App\Livewire\Admin\Pages\Users;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public Collection $roles;
    public string $selectedRole = '';

    public function mount()
    {
        $this->roles = Role::select('id', 'name')->get();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|unique:users,name',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'selectedRole' => 'required|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The user name is required.',
            'name.unique' => 'The user name must be unique/already taken.',
            'password.required' => 'The password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'selectedRole.required' => 'Please select a role.',
            'selectedRole.exists' => 'The selected role is invalid.',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => trim($this->name),
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $role = Role::findById($this->selectedRole);

        if ($role) {
            $user->assignRole($role->name);

            if ($role->name === 'owner') {
                $shop = new Shop();
                $shop->shop_name = $user->name;
                $shop->user_id = $user->id;
                $shop->save();
            }
        }


        session()->flash('success', 'User created successfully.');

        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'selectedRole',
        ]);

        return redirect()->route('livewire.admin.pages.users.view-user');
    }
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.pages.users.create-user');
    }
}
