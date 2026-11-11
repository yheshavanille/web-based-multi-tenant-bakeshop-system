<div class="login-responsive-shell relative overflow-hidden min-h-screen bg-white dark:bg-slate-900 flex items-center">
    <style>
        .login-responsive-shell {
            align-items: center;
        }

        .login-responsive-shell::before {
            content: "";
            position: absolute;
            inset: 0 50% 0 0;
            background-image: linear-gradient(rgba(15, 23, 42, .5), rgba(15, 23, 42, .5)),
            url("{{ asset('assets/img/login-bakery.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-responsive-container {
            max-width: none;
            min-height: 100vh;
            min-height: 100svh;
            padding: 0;
            position: relative;
            z-index: 1;
        }

        .login-responsive-grid {
            min-height: 100vh;
            min-height: 100svh;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 0;
        }

        .login-responsive-introduction {
            min-height: 0 !important;
            padding: 3rem 4rem 3rem clamp(2rem, 10.4vw, 9.5rem);
        }

        .login-responsive-introduction h1 {
            color: #f1f5f9;
            font-size: clamp(1.5rem, 2.2vw, 2rem);
            line-height: 1.15;
            text-align: left;
        }

        .login-responsive-introduction p {
            color: #e2e8f0;
            font-size: clamp(1rem, 1.2vw, 1.1rem);
            line-height: 1.65;
        }

        .login-responsive-introduction li {
            color: #e2e8f0;
            font-size: clamp(.9rem, 1vw, 1rem);
            line-height: 1.5;
        }

        .login-responsive-form-wrapper {
            justify-self: stretch;
            min-height: 100svh;
            padding: 3rem clamp(1.5rem, 5vw, 5rem);
        }

        .login-responsive-card {
            width: min(100%, 34rem);
            min-height: 24rem !important;
            padding: 2.5rem;
            background: rgba(255, 255, 255, .94);
            border-color: rgba(148, 163, 184, .4);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
        }

        .login-responsive-card label,
        .login-responsive-card>p {
            color: #334155;
        }

        .login-responsive-card input:not([type="checkbox"]) {
            color: #0f172a;
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .dark .login-responsive-card {
            background: rgba(30, 41, 59, .92);
            border-color: rgba(148, 163, 184, .2);
        }

        .dark .login-responsive-card label,
        .dark .login-responsive-card>p {
            color: #cbd5e1;
        }

        .dark .login-responsive-card input:not([type="checkbox"]) {
            color: #f1f5f9;
            background-color: #0f172a;
            border-color: #334155;
        }

        @media (max-width: 1023px) {
            .login-responsive-shell {
                align-items: flex-start;
            }

            .login-responsive-shell::before {
                display: none;
            }

            .login-responsive-container {
                max-width: 56rem;
                min-height: 100vh;
                min-height: 100svh;
                padding: 2rem clamp(1rem, 4vw, 2rem);
            }

            .login-responsive-grid {
                min-height: 0;
                grid-template-columns: minmax(0, 1fr);
                gap: 1.5rem;
            }

            .login-responsive-introduction {
                min-height: 0 !important;
                padding: clamp(1.5rem, 5vw, 3rem);
                border-radius: 1rem;
                background-image: linear-gradient(rgba(15, 23, 42, .58), rgba(15, 23, 42, .68)),
                url("{{ asset('assets/img/login-bakery.png') }}");
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            .login-responsive-introduction h1 {
                font-size: clamp(1.5rem, 3.8vw, 1.875rem);
            }

            .login-responsive-form-wrapper {
                min-height: 0;
                padding: 0;
                justify-self: center;
            }

            .login-responsive-card {
                width: min(100%, 48rem);
                min-height: 0 !important;
                padding: clamp(1.25rem, 4vw, 2.5rem);
            }

            .login-responsive-introduction,
            .login-responsive-form-wrapper {
                grid-column: auto;
                grid-row: auto;
            }

            .login-responsive-options {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
            }
        }

        @media (max-width: 420px) {
            .login-responsive-container {
                padding: 1rem 0.75rem;
            }

            .login-responsive-introduction {
                padding: 1.5rem;
            }

            .login-responsive-card {
                padding: 1.25rem;
            }

            .login-responsive-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>

    <div class="login-responsive-container max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
        <div class="login-responsive-grid grid grid-cols-2 gap-12 lg:gap-16 items-stretch">
            <!-- LEFT: Introduction -->
            <div class="login-responsive-introduction col-start-1 row-start-1 flex flex-col justify-center"
                style="min-height: 26rem;">
                <h1 class="text-2xl text-gray-900 dark:text-slate-100 font-bold leading-tight md:text-3xl lg:text-4xl">
                    Welcome to
                    Web-based
                    Multi-tenant Bakeshop
                    with Service Quality Reviews, Ratings, Sales Analytics, and E-payment Services </h1>
                <p class="mt-4 text-sm text-gray-600 dark:text-slate-300 leading-relaxed max-w-xl md:text-base">
                    Your all-in-one bakeshop management solution. Handle orders, track inventory, manage employees, and
                    gain real-time sales insights - all from a single platform. Built to help bakeshops in Victorias
                    City
                    grow and serve customers better.
                </p>
                <ul class="mt-4 space-y-2 text-xs text-gray-700 dark:text-slate-300 md:text-sm">
                    <li class="flex items-center gap-3"><span class="text-green-600 dark:text-green-400">&#10003;</span>
                        All-in-one solution
                    </li>
                    <li class="flex items-center gap-3"><span class="text-green-600 dark:text-green-400">&#10003;</span>
                        Orders, inventory,
                        employees, sales analytics</li>
                    <li class="flex items-center gap-3"><span class="text-green-600 dark:text-green-400">&#10003;</span>
                        Real-time insights
                    </li>
                    <li class="flex items-center gap-3"><span class="text-green-600 dark:text-green-400">&#10003;</span>
                        Local focus
                        (Victorias City)</li>
                    <li class="flex items-center gap-3"><span class="text-green-600 dark:text-green-400">&#10003;</span>
                        E-payment and Cash on Pickup
                    </li>
                </ul>
            </div>

            <!-- RIGHT: Login Form -->
            <div
                class="login-responsive-form-wrapper w-full col-start-2 row-start-1 justify-self-end flex flex-col justify-center self-center">
                <div class="login-responsive-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-8 max-sm:p-5 md:p-10 shadow-sm flex flex-col justify-center"
                    style="min-height: 26rem;">

                    @if (session()->has('status'))
                    <div
                        class="mb-4 p-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if ($errors->has('email') && str_contains($errors->first('email'), 'Too many login attempts'))
                    <div
                        class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-200 rounded-lg text-sm flex items-start gap-2">
                        <span class="text-lg flex-shrink-0">🚫</span>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                    @endif

                    <form wire:submit.prevent="login">
                        <div class="mb-5"> <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Email
                                Address</label> <input type="email" id="email" wire:model="email"
                                class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg sm:text-sm text-gray-900 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:border-amber-500 focus:ring-amber-500 @error('email') border-red-500 @enderror"
                                placeholder="you@example.com"> @error('email') <p class="text-xs text-red-500 mt-1">
                                {{
                                $message }}</p> @enderror </div>
                        <div class="mb-5">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Password</label>
                            <div class="relative flex items-center" style="position: relative;"
                                x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" id="password" wire:model="password"
                                    class="py-2.5 sm:py-3 px-4 pr-11 block w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg sm:text-sm text-gray-900 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:border-amber-500 focus:ring-amber-500 @error('password') border-red-500 @enderror"
                                    style="padding-right: 2.75rem;" placeholder="Enter your password">
                                <button type="button" @click="show = !show"
                                    style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; background: transparent; border: none;"
                                    class="text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 focus:outline-none">
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
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="login-responsive-options flex items-center justify-between text-sm mb-4"> <label
                                class="flex items-center gap-2 text-gray-600 dark:text-slate-300 cursor-pointer"> <input
                                    type="checkbox" wire:model="remember"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-700 text-amber-600 focus:ring-amber-500">
                                Remember me </label> <a href="{{ route('livewire.auth.forgot-password') }}"
                                class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 hover:underline">Forgot
                                password?</a> </div>
                        {{-- Sign In button — visible when NOT loading --}}
                        <button type="submit" wire:loading.remove wire:target="login"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-amber-500 hover:bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                            Sign In
                        </button>

                        {{-- Signing in button — visible ONLY while loading --}}
                        <button type="button" wire:loading wire:target="login" disabled
                            class="w-full py-3 px-4 text-sm font-medium rounded-lg bg-amber-500 text-white opacity-70 cursor-not-allowed">
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%;">
                                <svg class="animate-spin w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Signing in...
                            </span>
                        </button>
                    </form>
                    <p class="mt-8 text-center text-sm text-gray-500 dark:text-slate-400"> Don't have an account? <a
                            href="{{ route('livewire.auth.register') }}"
                            class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-medium hover:underline">
                            Register here </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>