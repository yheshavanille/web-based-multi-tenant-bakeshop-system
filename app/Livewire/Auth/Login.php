<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $layout = 'components.layouts.app';

    public function mount()
    {
        // ✅ Only store redirect if user explicitly clicked "Start Selling"
        if (request()->has('start_selling') && request()->get('start_selling') === 'true') {
            session()->put('redirect_after_login', route('livewire.guest.start-selling'));
        } else {
            // ✅ Clear any existing redirect if no start_selling parameter
            session()->forget('redirect_after_login');
        }
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ✅ Check if user exists and is active
        $user = User::where('email', $this->email)->first();

        if ($user) {
            // ✅ Check if user is suspended (is_active = false)
            if (isset($user->is_active) && !$user->is_active) {
                $this->addError('email', 'Your account has been suspended. Please contact support.');
                return;
            }

            // ✅ Check if user is soft deleted
            if ($user->trashed()) {
                $this->addError('email', 'Your account has been deactivated. Please contact support.');
                return;
            }
        }

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('email', 'Invalid credentials or account disabled');
            return;
        }

        request()->session()->regenerate();

        $user = Auth::user();

        // ✅ Double-check the authenticated user is active
        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            $this->addError('email', 'Your account has been suspended. Please contact support.');
            return;
        }

        // ✅ Check if there's a redirect stored from "Start Selling"
        if (session()->has('redirect_after_login')) {
            $redirectUrl = session()->pull('redirect_after_login');
            return redirect($redirectUrl);
        }

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
