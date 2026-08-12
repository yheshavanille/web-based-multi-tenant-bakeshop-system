<div>
    <!-- REGISTER PAGE -->
    <div id="register" class="bg-background">
        <div class="max-w-5xl px-4 xl:px-0 py-10 lg:py-20 mx-auto">

            <!-- TITLE -->
            <div class="max-w-3xl mb-10 lg:mb-14">
                <h2 class="text-foreground font-semibold text-2xl md:text-4xl md:leading-tight">
                    Create Your Account
                </h2>

                <p class="mt-1 text-muted-foreground-1">
                    Register to start managing your bakeshop.
                </p>
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 lg:gap-x-16">

                <!-- LEFT: FORM -->
                <div class="md:order-2">

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">

                        <form wire:submit.prevent="register">

                            <div class="space-y-6">

                                <!-- EMAIL -->
                                <div class="relative">
                                    <input type="email" wire:model="email" placeholder="Email"
                                        class="peer p-3 sm:p-4 block w-full bg-surface border border-line-1 rounded-lg text-foreground placeholder:text-transparent focus:outline-none focus:border-primary focus:pt-6 focus:pb-2"
                                        required>

                                    <label
                                        class="absolute top-0 start-0 p-3 sm:p-4 text-sm text-muted-foreground-1 pointer-events-none transition
                                        peer-focus:text-xs peer-focus:-translate-y-1.5
                                        peer-not-placeholder-shown:text-xs peer-not-placeholder-shown:-translate-y-1.5">
                                        Email Address
                                    </label>
                                </div>

                                @error('email')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror

                                <!-- PASSWORD -->
                                <div class="relative">
                                    <input type="password" wire:model="password" placeholder="Password"
                                        class="peer p-3 sm:p-4 block w-full bg-surface border border-line-1 rounded-lg text-foreground placeholder:text-transparent focus:outline-none focus:border-primary focus:pt-6 focus:pb-2"
                                        required>

                                    <label
                                        class="absolute top-0 start-0 p-3 sm:p-4 text-sm text-muted-foreground-1 pointer-events-none transition
                                        peer-focus:text-xs peer-focus:-translate-y-1.5
                                        peer-not-placeholder-shown:text-xs peer-not-placeholder-shown:-translate-y-1.5">
                                        Password
                                    </label>
                                </div>

                                @error('password')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror

                                <!-- CONFIRM PASSWORD -->
                                <div class="relative">
                                    <input type="password" wire:model="password_confirmation"
                                        placeholder="Confirm Password"
                                        class="peer p-3 sm:p-4 block w-full bg-surface border border-line-1 rounded-lg text-foreground placeholder:text-transparent focus:outline-none focus:border-primary focus:pt-6 focus:pb-2"
                                        required>

                                    <label
                                        class="absolute top-0 start-0 p-3 sm:p-4 text-sm text-muted-foreground-1 pointer-events-none transition
                                        peer-focus:text-xs peer-focus:-translate-y-1.5
                                        peer-not-placeholder-shown:text-xs peer-not-placeholder-shown:-translate-y-1.5">
                                        Confirm Password
                                    </label>
                                </div>

                                @error('password_confirmation')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                            <!-- LOGIN LINK -->
                            <div class="mt-4 text-sm text-muted-foreground-1">
                                Already have an account?
                                <a href="{{ route('livewire.auth.login') }}"
                                    class="text-primary font-medium hover:underline">
                                    Back to Login
                                </a>
                            </div>

                            <!-- BUTTON -->
                            <div class="mt-6">
                                <button type="submit"
                                    class="group inline-flex w-full justify-center items-center gap-x-2 py-3 px-4 bg-primary border border-primary-line text-primary-foreground font-medium text-sm rounded-lg hover:bg-primary-hover transition">
                                    Sign In
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                <!-- RIGHT: INFO PANEL (NO DUPLICATES) -->
                <div class="space-y-14">

                    <div class="flex gap-x-5">
                        <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>

                        <div>
                            <h4 class="text-foreground font-semibold">Multi-Tenant Bakeshop Platform</h4>
                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Manage products, monitor sales, and connect with customers all in one platform.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-x-5">
                        <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15V6" />
                            <path d="M18.5 18.5 12 22l-6.5-3.5" />
                            <path d="M12 22V12" />
                            <path d="m2 6 10 6 10-6" />
                        </svg>

                        <div>
                            <h4 class="text-foreground font-semibold">Easy Product Management</h4>
                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Add categories, upload product images, and manage your bakery inventory with ease.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-x-5">
                        <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2v20" />
                            <path d="m17 5-5-3-5 3" />
                        </svg>

                        <div>
                            <h4 class="text-foreground font-semibold">Built for Modern Bakeshops</h4>
                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Designed to simplify operations for bakery owners and improve customer experience.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>