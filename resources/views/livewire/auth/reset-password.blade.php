<div class="relative overflow-hidden min-h-screen bg-white flex items-center">
    <div class="max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-stretch">

            <!-- LEFT: Info -->
            <div class="flex flex-col justify-center">
                <h1 class="text-4xl text-gray-900 font-bold leading-tight md:text-5xl">
                    Set a new password
                </h1>
                <p class="mt-5 text-base text-gray-600 leading-relaxed max-w-xl md:text-lg">
                    Choose a strong password you haven't used before. For your security, we recommend a mix of letters,
                    numbers, and symbols.
                </p>
            </div>

            <!-- RIGHT: Form -->
            <div class="w-full justify-self-end">
                <div class="bg-white rounded-2xl border border-gray-200 p-8 md:p-10 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Create new password</h2>
                    <p class="text-sm text-gray-500 mb-6">Resetting for <span class="font-medium text-amber-600">{{
                            $email }}</span></p>

                    <form wire:submit.prevent="resetPassword">
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

                    <p class="mt-6 text-center text-sm text-gray-500">
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