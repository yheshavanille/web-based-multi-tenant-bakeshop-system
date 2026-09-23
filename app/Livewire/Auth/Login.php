<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public int $maxAttempts = 5;
    public int $decaySeconds = 60;

    protected $layout = 'components.layouts.app';

    public function mount()
    {
        if (request()->has('start_selling') && request()->get('start_selling') === 'true') {
            session()->put('redirect_after_login', route('livewire.guest.start-selling'));
        } else {
            session()->forget('redirect_after_login');
        }
    }

    private function throttleKey(): string
    {
        return 'login:' . strtolower(trim($this->email)) . '|' . request()->ip();
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', "Too many login attempts. Please try again in {$seconds} seconds.");
            return;
        }

        $user = User::where('email', $this->email)->first();

        if ($user) {
            if ($user->trashed()) {
                RateLimiter::hit($key, $this->decaySeconds);
                $this->addError('email', 'Your account has been deactivated. Please contact support.');
                return;
            }

            if (isset($user->is_active) && !$user->is_active) {
                $hasPendingOtp = \DB::table('password_otps')
                    ->where('email', $user->email)
                    ->whereNull('used_at')
                    ->where('expires_at', '>', now())
                    ->exists();

                if ($hasPendingOtp) {
                    RateLimiter::hit($key, $this->decaySeconds);
                    $this->addError('email', 'Please verify your email first. Check your inbox for the OTP.');
                    return;
                }

                RateLimiter::hit($key, $this->decaySeconds);
                $this->addError('email', 'Your account has been suspended. Please contact support.');
                return;
            }
        }

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            RateLimiter::hit($key, $this->decaySeconds);

            $remaining = RateLimiter::remaining($key, $this->maxAttempts);
            $message = 'Invalid credentials or account disabled';

            if ($remaining > 0 && $remaining <= 2) {
                $message .= " — {$remaining} attempt(s) remaining before lockout.";
            }

            $this->addError('email', $message);
            return;
        }

        RateLimiter::clear($key);

        request()->session()->regenerate();

        $user = Auth::user();

        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            $this->addError('email', 'Your account has been suspended. Please contact support.');
            return;
        }

        // ✅ NEW: Record the last login timestamp
        $user->update(['last_login_at' => now()]);

        if (session()->has('redirect_after_login')) {
            $redirectUrl = session()->pull('redirect_after_login');
            return redirect($redirectUrl);
        }

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
