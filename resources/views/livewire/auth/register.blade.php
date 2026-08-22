<div>
    <div class="relative overflow-hidden min-h-screen bg-white">
        <div class="max-w-7xl w-full mx-auto py-12 px-4 sm:px-6 md:py-20 lg:py-24 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- LEFT: Register Form -->
                <div>
                    <h1 class="text-3xl text-gray-900 font-bold md:text-4xl">
                        Create your account
                    </h1>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed max-w-md">
                        A complete platform for bakeshops to manage orders, inventory, customer reviews, and sales
                        analytics in one place.
                    </p>

                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm mt-6">
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
                    </div>

                    <p class="mt-4 text-center text-sm text-gray-500">
                        Already have an account?
                        <a href="{{ route('livewire.auth.login') }}"
                            class="text-amber-600 hover:text-amber-700 font-medium hover:underline">
                            Sign in here
                        </a>
                    </p>
                </div>

                <!-- RIGHT: System Introduction -->
                <div class="hidden lg:block">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-3xl p-10 text-white">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold mb-2">Professional Plan</h2>
                            <p class="text-amber-100 text-lg">Complete Bakeshop Management Platform</p>
                            <div class="mt-4 max-w-sm mx-auto">
                                <p class="text-amber-50/90 text-sm leading-relaxed">
                                    <span class="font-semibold text-white">Order Management</span> ·
                                    <span class="font-semibold text-white">Inventory Tracking</span> ·
                                    <span class="font-semibold text-white">Customer Reviews</span> ·
                                    <span class="font-semibold text-white">Sales Analytics</span>
                                </p>
                            </div>

                            <div class="mt-8 grid grid-cols-2 gap-4 text-left">
                                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                                    <p class="text-sm font-medium">Order Management</p>
                                    <p class="text-xs text-amber-100/70">Track and fulfill customer orders</p>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                                    <p class="text-sm font-medium">Inventory Tracking</p>
                                    <p class="text-xs text-amber-100/70">Real-time stock monitoring</p>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                                    <p class="text-sm font-medium">Customer Reviews</p>
                                    <p class="text-xs text-amber-100/70">Manage feedback and ratings</p>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                                    <p class="text-sm font-medium">Sales Analytics</p>
                                    <p class="text-xs text-amber-100/70">Data-driven business insights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>