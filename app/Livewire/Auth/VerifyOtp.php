<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class VerifyOtp extends Component
{
    public string $email = '';
    public string $code = '';
    public string $password = '';
    public string $password_confirmation = '';

    // true = user has entered a valid code, now show password fields
    public bool $codeVerified = false;

    protected $layout = 'components.layouts.app';

    public function mount()
    {
        $this->email = request()->query('email', '');
    }

    // Step A: user submits the 6-digit code
    public function verifyCode()
    {
        $this->validate([
            'code' => 'required|digits:6',
        ]);

        // Look up the latest valid code for this email
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

        // ✅ Code is correct — show the password fields
        $this->codeVerified = true;
    }

    // Step B: user submits new password
    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Re-verify the code (protects against someone skipping verification)
        $otp = DB::table('password_otps')
            ->where('email', $this->email)
            ->where('code', $this->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otp) {
            $this->codeVerified = false;
            $this->addError('code', 'Your code has expired. Please request a new one.');
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'No account found for this email.');
            return;
        }

        // ✅ Update the password
        $user->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        // ✅ Mark the OTP as used
        DB::table('password_otps')
            ->where('id', $otp->id)
            ->update(['used_at' => Carbon::now()]);

        // ✅ Redirect to login with a success message
        session()->flash('status', 'Password reset successfully! You can now log in with your new password.');
        return redirect()->route('livewire.auth.login');
    }

    public function render()
    {
        return view('livewire.auth.verify-otp');
    }
}
