<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Rules\PersonName;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Register extends Component
{
    public $name, $email, $password, $password_confirmation;

    // ✅ Rate limit config: 3 registrations per hour per IP
    public int $maxAttempts = 3;
    public int $decaySeconds = 3600; // 1 hour

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
        $key = 'register:' . request()->ip();

        // ✅ Rate limit registration attempts
        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            $this->addError('email', "Too many registration attempts. Please try again in {$minutes} minute(s).");
            return;
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255', new PersonName],
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8|same:password_confirmation',
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.dns' => 'The email domain does not appear to exist. Please check your email.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.same' => 'Passwords do not match.',
        ]);

        // ✅ Count the attempt (whether it succeeds or fails)
        RateLimiter::hit($key, $this->decaySeconds);

        $user = User::create([
            'name' => trim($this->name),
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('customer');

        // ✅ Check if user came from "Start Selling" flow
        if (session()->has('redirect_after_register')) {
            return redirect()->to(route('livewire.auth.login', ['start_selling' => 'true']));
        }

        return redirect()->to(route('livewire.auth.login'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
