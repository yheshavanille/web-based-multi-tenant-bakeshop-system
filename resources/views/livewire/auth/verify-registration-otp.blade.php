<div class="min-h-screen bg-white flex items-center">
    <div class="max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-stretch">

            <!-- LEFT: Info -->
            <div class="flex flex-col justify-center" style="min-height: 26rem;">
                <h1 class="text-4xl text-gray-900 font-bold leading-tight md:text-5xl">
                    Verify your email
                </h1>
                <p class="mt-5 text-base text-gray-600 leading-relaxed max-w-xl md:text-lg">
                    We sent a 6-digit verification code to
                    <span class="font-semibold text-amber-600">{{ $email }}</span>.
                    Enter it below to activate your account.
                </p>
                <p class="mt-4 text-sm text-gray-500">
                    The code expires in 10 minutes.
                </p>
                <p class="mt-3 text-sm text-gray-500">
                    Didn't get it? Check your spam folder, or use the resend button.
                </p>
            </div>

            <!-- RIGHT: Form -->
            <div class="w-full justify-self-end">
                <div class="bg-white rounded-2xl border border-gray-200 p-8 md:p-10 shadow-sm flex flex-col justify-start"
                    style="min-height: 26rem;">

                    <h2 class="text-xl font-bold text-gray-800 mb-2">Verification code</h2>
                    <p class="text-sm text-gray-500 mb-6">Enter the 6-digit code from your email</p>

                    @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div
                        class="mb-5 p-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-xs flex items-start gap-2">
                        <span class="text-base leading-none mt-0.5">💡</span>
                        <span>Codes are case-insensitive and valid for 10 minutes. You can request a new one
                            after 60 seconds.</span>
                    </div>

                    <form wire:submit.prevent="verify">
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
                            <span wire:loading.remove wire:target="verify">Verify Email</span>
                            <span wire:loading wire:target="verify">Verifying...</span>
                        </button>
                    </form>

                    <div class="mt-4 flex items-center justify-between text-sm">
                        <button type="button" wire:click="resend" wire:loading.attr="disabled"
                            class="text-amber-600 hover:text-amber-700 font-medium hover:underline disabled:opacity-60">
                            <span wire:loading.remove wire:target="resend">Resend code</span>
                            <span wire:loading wire:target="resend">Sending...</span>
                        </button>
                        <a href="{{ route('livewire.auth.register') }}"
                            class="text-gray-500 hover:text-gray-700 hover:underline">
                            Use a different email
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>