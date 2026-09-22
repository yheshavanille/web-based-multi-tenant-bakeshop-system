<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\AccountOtpNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class VerifyRegistrationOtp extends Component
{
    public string $email = '';
    public string $code = '';

    protected $layout = 'components.layouts.app';

    public function mount()
    {
        $this->email = request()->query('email', '');
    }

    public function verify()
    {
        $this->validate([
            'code' => 'required|digits:6',
        ]);

        // Look up a valid, unused, unexpired code for this email
        $otp = DB::table('password_otps')
            ->where('email', $this->email)
            ->where('code', $this->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otp) {
            $this->addError('code', 'Invalid or expired code. Please try again.');
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Account not found. Please register again.');
            return;
        }

        // ✅ Activate the account
        $user->update(['is_active' => true]);

        // ✅ Mark the OTP as used
        DB::table('password_otps')
            ->where('id', $otp->id)
            ->update(['used_at' => Carbon::now()]);

        session()->flash('status', 'Email verified! You can now log in.');

        // ✅ Preserve the "start selling" flow if it was set
        if (session()->has('redirect_after_register')) {
            return redirect()->route('livewire.auth.login', ['start_selling' => 'true']);
        }

        return redirect()->route('livewire.auth.login');
    }

    /**
     * ✅ Resend the OTP (rate-limited: 1 per 60 seconds)
     */
    public function resend()
    {
        $key = 'resend-otp:' . $this->email;

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('code', "Please wait {$seconds} seconds before requesting a new code.");
            return;
        }

        RateLimiter::hit($key, 60);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Account not found.');
            return;
        }

        // Generate a fresh code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_otps')->where('email', $user->email)->delete();
        DB::table('password_otps')->insert([
            'email'      => $user->email,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->notify(new AccountOtpNotification($code));

        session()->flash('status', 'A new verification code has been sent to your email.');
        $this->code = '';
    }

    public function render()
    {
        return view('livewire.auth.verify-registration-otp');
    }
}
