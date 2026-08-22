<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $layout = 'components.layouts.app';

    public function getShowDemoCredentialsProperty(): bool
    {
        return config('app.show_demo_credentials', false)
            && app()->environment(['local', 'staging', 'testing'])
            && ($this->demoLoginEmail || $this->hasDemoLoginPassword);
    }

    public function getDemoLoginEmailProperty(): ?string
    {
        $email = config('app.demo_login_email');

        return filled($email) ? (string) $email : null;
    }

    public function getHasDemoLoginPasswordProperty(): bool
    {
        return filled(config('app.demo_login_password'));
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('email', 'Invalid credentials or account disabled');
            return;
        }

        request()->session()->regenerate();

        $user = Auth::user();

        // Role-based redirect
        return match (true) {
            $user->hasRole('super_admin') => redirect()->route('livewire.admin.admin-dashboard'),
            $user->hasRole('employee') => redirect()->route('livewire.employee.dashboard'),
            $user->hasRole('owner') => redirect()->route('livewire.customer.dashboard'),
            default => redirect()->route('livewire.customer.dashboard'),
        };
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
