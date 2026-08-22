<div>
    <div id="login" class="relative min-h-screen overflow-hidden bg-background">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-20 -left-12 h-72 w-72 rounded-full bg-primary/15 blur-3xl"></div>
            <div class="absolute -bottom-24 right-0 h-80 w-80 rounded-full bg-primary/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-16">
            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-12">
                <div class="order-1">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-line-1 bg-layer px-3 py-1.5 text-xs font-medium text-muted-foreground-1 transition hover:border-primary/40 hover:text-foreground">
                        <span class="text-base">🧁</span>
                        BakeshopHub • Seller & Customer Portal
                    </div>

                    <h1 class="text-3xl font-semibold tracking-tight text-foreground sm:text-4xl">
                        Welcome back to your bakery dashboard
                    </h1>
                    <p class="mt-3 max-w-xl text-sm text-muted-foreground-1 sm:text-base">
                        Sign in to monitor orders, manage products, and keep your bakery thriving with real-time insights.
                    </p>

                    <div class="mt-8 rounded-2xl border border-line-1 bg-surface/90 p-5 shadow-sm backdrop-blur-sm transition duration-300 hover:shadow-md sm:p-7">
                        <form wire:submit.prevent="login" class="space-y-5">
                            <div>
                                <div class="relative">
                                    <input type="email" id="email" wire:model="email" placeholder=" "
                                        class="peer block w-full rounded-xl border border-line-1 bg-background px-4 pb-2.5 pt-6 text-sm text-foreground transition duration-300 placeholder:text-transparent focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                        required>
                                    <label for="email"
                                        class="pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 bg-transparent px-1 text-sm text-muted-foreground-1 transition-all duration-200 peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs peer-focus:font-medium peer-focus:text-primary peer-[:not(:placeholder-shown)]:top-3 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium @error('email') text-red-500 peer-focus:text-red-500 @enderror">
                                        Email Address
                                    </label>
                                </div>
                                @error('email')
                                    <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="relative">
                                    <input type="password" id="password" wire:model="password" placeholder=" "
                                        class="peer block w-full rounded-xl border border-line-1 bg-background px-4 pb-2.5 pt-6 text-sm text-foreground transition duration-300 placeholder:text-transparent focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                        required>
                                    <label for="password"
                                        class="pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 bg-transparent px-1 text-sm text-muted-foreground-1 transition-all duration-200 peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs peer-focus:font-medium peer-focus:text-primary peer-[:not(:placeholder-shown)]:top-3 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium @error('password') text-red-500 peer-focus:text-red-500 @enderror">
                                        Password
                                    </label>
                                </div>
                                @error('password')
                                    <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between gap-4 pt-1">
                                <label for="remember" class="inline-flex items-center gap-2 text-sm text-muted-foreground-1">
                                    <input id="remember" type="checkbox" wire:model="remember"
                                        class="size-4 rounded border-line-1 text-primary focus:ring-primary/40">
                                    Remember me
                                </label>

                                <span class="rounded-full bg-layer px-2.5 py-1 text-[11px] font-medium text-muted-foreground-1">Secure login</span>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="login"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-primary-line bg-primary px-4 py-3 text-sm font-medium text-primary-foreground transition duration-300 hover:-translate-y-0.5 hover:bg-primary-hover hover:shadow-lg hover:shadow-primary/20 disabled:cursor-not-allowed disabled:opacity-70">
                                <span wire:loading.remove wire:target="login">Sign In</span>
                                <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
                                    <span class="size-4 animate-spin rounded-full border-2 border-primary-foreground/40 border-t-primary-foreground"></span>
                                    Signing in...
                                </span>
                            </button>
                        </form>

                        <div class="mt-5 rounded-xl border border-dashed border-primary/35 bg-primary/8 p-4 text-sm transition duration-300 hover:border-primary/50">
                            <p class="font-medium text-foreground">Demo Credentials</p>
                            <p class="mt-1 text-xs text-muted-foreground-1">Email: <span class="font-medium text-foreground">customer@example.com</span></p>
                            <p class="text-xs text-muted-foreground-1">Password: <span class="font-medium text-foreground">password</span></p>
                        </div>
                    </div>

                    <p class="mt-5 text-sm text-muted-foreground-1">
                        Don't have an account yet?
                        <a href="{{ route('livewire.auth.register') }}"
                            class="font-medium text-primary transition hover:text-primary-hover hover:underline">
                            Register here
                        </a>
                    </p>
                </div>

                <div class="order-2 rounded-3xl bg-gradient-to-br from-primary/95 via-primary to-primary-hover p-6 text-primary-foreground shadow-xl shadow-primary/20 transition duration-500 hover:-translate-y-1 sm:p-8 lg:p-10">
                    <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-medium tracking-wide">
                        Freshly baked tools for growth
                    </span>

                    <h2 class="mt-5 text-2xl font-semibold leading-tight sm:text-3xl">
                        Grow your bakeshop with one simple platform
                    </h2>
                    <p class="mt-3 text-sm text-primary-foreground/85 sm:text-base">
                        Centralize products, orders, reviews, and customer communication with a storefront designed for modern bakery teams.
                    </p>

                    <div class="mt-7 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm transition duration-300 hover:bg-white/15">
                            <p class="text-base">📦</p>
                            <p class="mt-1 text-sm font-medium">Smart Order Flow</p>
                            <p class="mt-1 text-xs text-primary-foreground/80">Track every order from checkout to pickup.</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm transition duration-300 hover:bg-white/15">
                            <p class="text-base">📊</p>
                            <p class="mt-1 text-sm font-medium">Daily Sales Snapshot</p>
                            <p class="mt-1 text-xs text-primary-foreground/80">See revenue trends in a clear dashboard.</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm transition duration-300 hover:bg-white/15 sm:col-span-2">
                            <p class="text-base">🤝</p>
                            <p class="mt-1 text-sm font-medium">Built for multi-tenant bakeries</p>
                            <p class="mt-1 text-xs text-primary-foreground/80">Manage multiple shops and teams with confidence.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
