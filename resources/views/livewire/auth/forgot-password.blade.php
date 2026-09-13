<div class="forgot-password-responsive-shell relative overflow-hidden min-h-screen bg-white flex items-center">
    <style>
        @media (max-width: 1023px) {
            .forgot-password-responsive-shell {
                align-items: flex-start;
            }

            .forgot-password-responsive-container {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            .forgot-password-responsive-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 2rem;
            }

            .forgot-password-responsive-introduction,
            .forgot-password-responsive-form-wrapper {
                grid-column: auto;
                grid-row: auto;
                min-height: 0 !important;
            }

            .forgot-password-responsive-card {
                min-height: 0 !important;
                padding: 1.5rem;
            }
        }
    </style>

    <div
        class="forgot-password-responsive-container max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
        <div class="forgot-password-responsive-grid grid grid-cols-2 gap-12 lg:gap-16 items-stretch">

            <!-- LEFT: Info -->
            <div class="forgot-password-responsive-introduction col-start-1 row-start-1 flex flex-col justify-center"
                style="min-height: 26rem;">
                <h1 class="text-4xl text-gray-900 font-bold leading-tight md:text-5xl">
                    Forgot your password?
                </h1>
                <p class="mt-5 text-base text-gray-600 leading-relaxed max-w-xl md:text-lg">
                    No worries — it happens. Enter the email address linked to your account and we'll send you a 6-digit
                    verification code.
                </p>
                <p class="mt-4 text-sm text-gray-500">
                    The code will expire in 10 minutes for your security.
                </p>
            </div>

            <!-- RIGHT: Form -->
            <div class="forgot-password-responsive-form-wrapper w-full col-start-2 row-start-1 justify-self-end">
                <div class="forgot-password-responsive-card bg-white rounded-2xl border border-gray-200 p-8 md:p-10 shadow-sm flex flex-col justify-start"
                    style="min-height: 26rem;">

                    <h2 class="text-xl font-bold text-gray-800 mb-2">Reset your password</h2>
                    <p class="text-sm text-gray-500 mb-6">We'll email you a verification code</p>

                    @if (session('status'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form wire:submit.prevent="sendOtp">
                        <div class="mb-5">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input type="email" id="email" wire:model="email"
                                class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 @error('email') border-red-500 @enderror"
                                placeholder="you@example.com">
                            @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-amber-500 hover:bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition disabled:opacity-60">
                            <span wire:loading.remove wire:target="sendOtp">Send Verification Code</span>
                            <span wire:loading wire:target="sendOtp">Sending...</span>
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-500">
                        Remember your password?
                        <a href="{{ route('livewire.auth.login') }}"
                            class="text-amber-600 hover:text-amber-700 font-medium hover:underline">
                            Back to login
                        </a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>