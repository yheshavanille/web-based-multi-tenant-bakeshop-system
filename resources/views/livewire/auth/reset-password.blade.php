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
                            <div class="relative flex items-center" style="position: relative;"
                                x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" id="password" wire:model="password"
                                    class="py-2.5 sm:py-3 px-4 pr-11 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 @error('password') border-red-500 @enderror"
                                    style="padding-right: 2.75rem;" placeholder="At least 8 characters">
                                <button type="button" @click="show = !show"
                                    style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; background: transparent; border: none;"
                                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm New Password
                            </label>
                            <div class="relative flex items-center" style="position: relative;"
                                x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" id="password_confirmation"
                                    wire:model="password_confirmation"
                                    class="py-2.5 sm:py-3 px-4 pr-11 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500"
                                    style="padding-right: 2.75rem;" placeholder="Re-enter your password">
                                <button type="button" @click="show = !show"
                                    style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; background: transparent; border: none;"
                                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
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
