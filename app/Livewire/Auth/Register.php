<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name, $email, $password, $password_confirmation;
    public function register()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:password_confirmation',
        ]);

        $user = User::create([
            'name' => $this->name ?? 'Customer',
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('customer');

        return redirect()->to(route('livewire.auth.login'));
    }



    public function render()
    {
        return view('livewire.auth.register');
    }
}
