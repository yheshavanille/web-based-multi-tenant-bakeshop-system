<div>
    <!-- Contact / Login Section -->
    <div id="login" class="bg-background">
        <div class="max-w-5xl px-4 xl:px-0 py-10 lg:py-20 mx-auto">

            <!-- Title -->
            <div class="max-w-3xl mb-10 lg:mb-14">
                <h2 class="text-foreground font-semibold text-2xl md:text-4xl md:leading-tight">
                    Welcome Back
                </h2>

                <p class="mt-1 text-muted-foreground-1">
                    Login to manage your shop or browse products from your favorite bakeshops.
                </p>
            </div>
            <!-- End Title -->

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 lg:gap-x-16">

                <!-- LOGIN FORM -->
                <div class="md:order-2 pb-10 mb-10 md:pb-0 md:mb-0">

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">

                        <form wire:submit.prevent="login">

                            <div class="space-y-4">

                                <!-- EMAIL -->
                                <div class="relative">
                                    <input type="email" id="email" wire:model="email" placeholder="Email" class="peer p-3 sm:p-4 block w-full bg-surface border border-line-1 rounded-lg sm:text-sm text-foreground placeholder:text-transparent focus:outline-hidden focus:ring-0 focus:border-primary
                        focus:pt-6 focus:pb-2 not-placeholder-shown:pt-6 not-placeholder-shown:pb-2" required>

                                    <label for="email" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-muted-foreground-1 text-sm truncate pointer-events-none transition ease-in-out duration-100
                        peer-focus:text-xs peer-focus:-translate-y-1.5
                        peer-not-placeholder-shown:text-xs peer-not-placeholder-shown:-translate-y-1.5">
                                        Email Address
                                    </label>
                                </div>

                                @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror


                                <!-- PASSWORD -->
                                <div class="relative">
                                    <input type="password" id="password" wire:model="password" placeholder="Password"
                                        class="peer p-3 sm:p-4 block w-full bg-surface border border-line-1 rounded-lg sm:text-sm text-foreground placeholder:text-transparent focus:outline-hidden focus:ring-0 focus:border-primary
                        focus:pt-6 focus:pb-2 not-placeholder-shown:pt-6 not-placeholder-shown:pb-2" required>

                                    <label for="password" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-muted-foreground-1 text-sm truncate pointer-events-none transition ease-in-out duration-100
                        peer-focus:text-xs peer-focus:-translate-y-1.5
                        peer-not-placeholder-shown:text-xs peer-not-placeholder-shown:-translate-y-1.5">
                                        Password
                                    </label>
                                </div>

                                @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror


                                <!-- REMEMBER -->
                                <div class="flex items-center">
                                    <input id="remember" type="checkbox" wire:model="remember"
                                        class="shrink-0 size-4 border-gray-300 rounded text-primary focus:ring-primary">

                                    <label for="remember" class="ms-2 text-sm text-muted-foreground-1">
                                        Remember me
                                    </label>
                                </div>

                            </div>

                            <!-- REGISTER LINK -->
                            <div class="mt-4 text-sm text-muted-foreground-1">
                                Don't have an account yet?
                                <a href="{{ route('livewire.auth.register') }}"
                                    class="text-primary font-medium hover:underline">
                                    Register here
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
                <!-- End Login Form -->


                <!-- RIGHT SIDE CONTENT -->
                <div class="space-y-14">

                    <!-- ITEM -->
                    <div class="flex gap-x-5">
                        <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>

                        <div class="grow">
                            <h4 class="text-foreground font-semibold">
                                Multi-Tenant Bakeshop Platform
                            </h4>

                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Manage products, monitor sales, and connect with customers all in one platform.
                            </p>
                        </div>
                    </div>

                    <!-- ITEM -->
                    <div class="flex gap-x-5">
                        <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15V6" />
                            <path d="M18.5 18.5 12 22l-6.5-3.5" />
                            <path d="M12 22V12" />
                            <path d="m2 6 10 6 10-6" />
                            <path d="M2 6 12 2l10 4" />
                        </svg>

                        <div class="grow">
                            <h4 class="text-foreground font-semibold">
                                Easy Product Management
                            </h4>

                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Add categories, upload product images, and manage your bakery inventory with ease.
                            </p>
                        </div>
                    </div>

                    <!-- ITEM -->
                    <div class="flex gap-x-5">
                        <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20" />
                            <path d="m17 5-5-3-5 3" />
                            <path d="m17 19-5 3-5-3" />
                        </svg>

                        <div class="grow">
                            <h4 class="text-foreground font-semibold">
                                Want to be a tenant? Contact us now!
                            </h4>

                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Contact No.:090912312313
                            </p>

                            <p class="mt-1 text-muted-foreground-1 text-sm">
                                Email: shesh@gmail.com
                            </p>
                        </div>
                    </div>

                </div>
                <!-- End Right Side -->

            </div>
            <!-- End Grid -->

        </div>
    </div>
</div>