<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\PasswordOtpNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public bool $sent = false;

    protected $layout = 'components.layouts.app';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendOtp()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        // If user exists, generate + send OTP. If not, still show the "sent" state
        // (so attackers can't probe which emails are registered).
        if ($user) {
            // ✅ Generate a 6-digit code
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // ✅ Delete any old codes for this email (fresh start)
            DB::table('password_otps')->where('email', $this->email)->delete();

            // ✅ Save the new code with a 10-minute expiry
            DB::table('password_otps')->insert([
                'email'      => $this->email,
                'code'       => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ✅ Send the email
            $user->notify(new PasswordOtpNotification($code));
        }

        $this->sent = true;

        // ✅ After a tiny delay, redirect to the verify page
        return redirect()->route('livewire.auth.verify-otp', ['email' => $this->email]);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
