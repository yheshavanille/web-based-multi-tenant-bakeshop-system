<div class="verify-otp-responsive-shell relative overflow-hidden min-h-screen bg-white flex items-center">
    <style>
        @media (max-width: 1023px) {
            .verify-otp-responsive-shell {
                align-items: flex-start;
            }

            .verify-otp-responsive-container {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            .verify-otp-responsive-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 2rem;
            }

            .verify-otp-responsive-introduction,
            .verify-otp-responsive-form-wrapper {
                grid-column: auto;
                grid-row: auto;
                min-height: 0 !important;
            }

            .verify-otp-responsive-card {
                min-height: 0 !important;
                padding: 1.5rem;
            }
        }
    </style>

    <div class="verify-otp-responsive-container max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
        <div class="verify-otp-responsive-grid grid grid-cols-2 gap-12 lg:gap-16 items-stretch">

            <!-- LEFT: Info -->
            <div class="verify-otp-responsive-introduction col-start-1 row-start-1 flex flex-col justify-center"
                style="min-height: 26rem;">
                <h1 class="text-4xl text-gray-900 font-bold leading-tight md:text-5xl">
                    @if($codeVerified)
                    Set a new password
                    @else
                    Enter your code
                    @endif
                </h1>
                <p class="mt-5 text-base text-gray-600 leading-relaxed max-w-xl md:text-lg">
                    @if($codeVerified)
                    Your identity is verified. Choose a strong password you haven't used before.
                    @else
                    We sent a 6-digit verification code to
                    <span class="font-semibold text-amber-600">{{ $email }}</span>.
                    Enter it below to continue.
                    @endif
                </p>
                @if(!$codeVerified)
                <p class="mt-4 text-sm text-gray-500">
                    The code expires in 10 minutes.
                </p>
                @endif
            </div>

            <!-- RIGHT: Form -->
            <div class="verify-otp-responsive-form-wrapper w-full col-start-2 row-start-1 justify-self-end">
                <div class="verify-otp-responsive-card bg-white rounded-2xl border border-gray-200 p-8 md:p-10 shadow-sm flex flex-col justify-start"
                    style="min-height: 26rem;">

                    @if($codeVerified)
                    {{-- ============= STEP 2: New Password ============= --}}
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Create new password</h2>
                    <p class="text-sm text-gray-500 mb-6">Resetting for <span class="font-medium text-amber-600">{{
                            $email }}</span></p>

                    <form wire:submit.prevent="resetPassword">
                        <div class="mb-5">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password
                            </label>
                            <input type="password" id="password" wire:model="password"
                                class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 @error('password') border-red-500 @enderror"
                                placeholder="At least 8 characters">
                            @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm New Password
                            </label>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500"
                                placeholder="Re-enter your password">
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-amber-500 hover:bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition disabled:opacity-60">
                            <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                            <span wire:loading wire:target="resetPassword">Resetting...</span>
                        </button>
                    </form>

                    @else
                    {{-- ============= STEP 1: Enter Code ============= --}}
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Verification code</h2>
                    <p class="text-sm text-gray-500 mb-6">Check your email for the 6-digit code</p>

                    @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{-- ✅ Friendly info box --}}
                    <div
                        class="mb-5 p-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-xs flex items-start gap-2">
                        <span class="text-base leading-none mt-0.5">💡</span>
                        <span>Didn't get anything? Check your spam folder or try again with the correct email.</span>
                    </div>

                    <form wire:submit.prevent="verifyCode">
                        <div class="mb-5">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                                6-Digit Code
                            </label>
                            <input type="text" id="code" wire:model="code" inputmode="numeric" maxlength="6"
                                autocomplete="one-time-code"
                                class="py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg text-center text-2xl tracking-[0.5em] font-bold text-gray-900 placeholder:text-gray-300 placeholder:tracking-normal placeholder:text-base placeholder:font-normal focus:border-amber-500 focus:ring-amber-500 @error('code') border-red-500 @enderror"
                                placeholder="000000">
                            @error('code')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-amber-500 hover:bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition disabled:opacity-60">
                            <span wire:loading.remove wire:target="verifyCode">Verify Code</span>
                            <span wire:loading wire:target="verifyCode">Verifying...</span>
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-500">
                        Wrong email?
                        <a href="{{ route('livewire.auth.forgot-password') }}"
                            class="text-amber-600 hover:text-amber-700 font-medium hover:underline">
                            Try again
                        </a>
                    </p>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>