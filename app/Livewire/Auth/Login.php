<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $layout = 'components.layouts.app';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Invalid credentials');
            return;
        }

        if (isset($user->is_active) && !$user->is_active) {
            $this->addError('email', 'Account is disabled. Contact admin.');
            return;
        }

        if (!Hash::check($this->password, $user->password)) {
            $this->addError('email', 'Invalid credentials');
            return;
        }

        Auth::login($user, $this->remember);
        request()->session()->regenerate();

        // role-based redirect
        return match (true) {
            $user->hasRole('super_admin') => redirect()->route('livewire.admin.admin-dashboard'),
            $user->hasRole('owner') => redirect()->route('livewire.owner.dashboard'),
            $user->hasRole('customer') => redirect()->route('livewire.customer.dashboard'),
            default => redirect('/'),
        };
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
