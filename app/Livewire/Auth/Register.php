<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\AccountOtpNotification;
use App\Rules\PersonName;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Register extends Component
{
    public $name, $email, $password, $password_confirmation;

    //  Rate limit config: 3 registrations per hour per IP
    public int $maxAttempts = 3;
    public int $decaySeconds = 3600; // 1 hour

    public function mount()
    {
        //  Only store redirect if user explicitly clicked "Start Selling"
        if (request()->has('start_selling') && request()->get('start_selling') === 'true') {
            session()->put('redirect_after_register', route('livewire.guest.start-selling'));
        } else {
            session()->forget('redirect_after_register');
        }
    }

    public function register()
    {
        $key = 'register:' . request()->ip();

        //  Rate limit registration attempts
        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            $this->addError('email', "Too many registration attempts. Please try again in {$minutes} minute(s).");
            return;
        }

        //  Custom email rule: block ONLY if email belongs to a verified account
        //    (unverified accounts can be overwritten by re-registering)
        $this->validate([
            'name' => ['required', 'string', 'max:255', new PersonName],
            'email' => [
                'required',
                'email:rfc,dns',
                function ($attribute, $value, $fail) {
                    $existing = User::where('email', $value)->first();
                    if ($existing && $existing->is_active) {
                        $fail('This email is already registered.');
                    }
                },
            ],
            'password' => 'required|min:8|same:password_confirmation',
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.dns' => 'The email domain does not appear to exist. Please check your email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.same' => 'Passwords do not match.',
        ]);

        //  Count the attempt
        RateLimiter::hit($key, $this->decaySeconds);

        //  Handle existing unverified account (overwrite it)
        $existingUnverified = User::where('email', $this->email)
            ->where('is_active', false)
            ->first();

        if ($existingUnverified) {
            // Overwrite the previous unverified registration with the new info
            $existingUnverified->update([
                'name' => trim($this->name),
                'password' => Hash::make($this->password),
            ]);
            $user = $existingUnverified;
        } else {
            $user = User::create([
                'name' => trim($this->name),
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_active' => false, //  Unverified until OTP confirmed
            ]);

            $user->assignRole('customer');
        }

        //  Generate + save OTP
        $this->generateAndSendOtp($user);

        //  Redirect to OTP verification page
        return redirect()->route('livewire.auth.verify-registration-otp', ['email' => $user->email]);
    }

    /**
     *  Generate a 6-digit code, store it, and email it.
     */
    private function generateAndSendOtp(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any old codes for this email
        DB::table('password_otps')->where('email', $user->email)->delete();

        // Save the new code with 10-minute expiry
        DB::table('password_otps')->insert([
            'email'      => $user->email,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send the email
        $user->notify(new AccountOtpNotification($code));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
