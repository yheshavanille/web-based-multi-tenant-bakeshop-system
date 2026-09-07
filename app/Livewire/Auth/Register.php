<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name, $email, $password, $password_confirmation;

    public function mount()
    {
        // ✅ Only store redirect if user explicitly clicked "Start Selling"
        if (request()->has('start_selling') && request()->get('start_selling') === 'true') {
            session()->put('redirect_after_register', route('livewire.guest.start-selling'));
        } else {
            // ✅ Clear any existing redirect if no start_selling parameter
            session()->forget('redirect_after_register');
        }
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:password_confirmation',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('customer');

        // ✅ Check if user came from "Start Selling" flow
        if (session()->has('redirect_after_register')) {
            // ✅ Redirect to LOGIN page with start_selling parameter
            // The login page will handle the redirect to Start Selling
            return redirect()->to(route('livewire.auth.login', ['start_selling' => 'true']));
        }

        // ✅ Default: redirect to login page
        return redirect()->to(route('livewire.auth.login'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
