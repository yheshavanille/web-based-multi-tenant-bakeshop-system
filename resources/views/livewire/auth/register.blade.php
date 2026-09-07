<div>
    <div class="relative overflow-hidden min-h-screen bg-white flex items-center">
        <div class="max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
            <div class="grid grid-cols-2 gap-12 lg:gap-16 items-stretch">

                <!-- LEFT: Introduction -->
                <div class="col-start-1 row-start-1 flex flex-col justify-center" style="min-height: 26rem;">
                    <h1 class="text-5xl text-gray-900 font-bold leading-tight md:text-6xl">
                        Create your account
                    </h1>
                    <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-xl md:text-xl">
                        Your all-in-one bakeshop management solution. Handle orders, track inventory, manage employees,
                        and gain real-time sales insights - all from a single platform. Built to help bakeshops in
                        Victorias City grow and serve customers better.
                    </p>
                    <ul class="mt-6 space-y-3 text-base text-gray-700 md:text-lg">
                        <li class="flex items-center gap-3"><span class="text-green-600">&#10003;</span> All-in-one
                            solution</li>
                        <li class="flex items-center gap-3"><span class="text-green-600">&#10003;</span> Orders,
                            inventory, employees, sales analytics</li>
                        <li class="flex items-center gap-3"><span class="text-green-600">&#10003;</span> Real-time
                            insights</li>
                        <li class="flex items-center gap-3"><span class="text-green-600">&#10003;</span> Local focus
                            (Victorias City)</li>
                        <li class="flex items-center gap-3"><span class="text-green-600">&#10003;</span> Growth-oriented
                        </li>
                    </ul>
                </div>

                <!-- RIGHT: Register Form -->
                <div class="w-full col-start-2 row-start-1 justify-self-end">
                    <div class="bg-white rounded-2xl border border-gray-200 p-8 md:p-10 shadow-sm">
                        <form wire:submit.prevent="register">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" wire:model="name"
                                    class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 @error('name') border-red-500 @enderror"
                                    placeholder="Juan Dela Cruz">
                                @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                    Address</label>
                                <input type="email" id="email" wire:model="email"
                                    class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 @error('email') border-red-500 @enderror"
                                    placeholder="you@example.com">
                                @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" id="password" wire:model="password"
                                    class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500 @error('password') border-red-500 @enderror"
                                    placeholder="Enter your password">
                                @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                    class="py-2.5 sm:py-3 px-4 block w-full bg-gray-50 border border-gray-200 rounded-lg sm:text-sm text-gray-900 placeholder:text-gray-400 focus:border-amber-500 focus:ring-amber-500"
                                    placeholder="Confirm your password">
                            </div>

                            <button type="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-amber-500 hover:bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                                Create Account
                            </button>
                        </form>

                        <p class="mt-8 text-center text-sm text-gray-500">
                            Already have an account?
                            <a href="{{ route('livewire.auth.login') }}"
                                class="text-amber-600 hover:text-amber-700 font-medium hover:underline">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>