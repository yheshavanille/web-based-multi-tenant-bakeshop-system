<div>
    <div class="max-w-2xl mx-auto">
        <!-- Header with Super Admin Badge -->
        <div class="flex items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <span
                class="text-xs font-medium px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                👑 Super Admin
            </span>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>

            <form wire:submit.prevent="updateProfile" class="space-y-4">
                <!-- Profile Picture -->
                <div class="flex items-center gap-6">
                    <div class="relative flex-shrink-0">
                        @if($temp_profile_picture_preview)
                        <img src="{{ $temp_profile_picture_preview }}" alt="Profile Picture Preview"
                            class="w-20 h-20 rounded-full object-cover border-2 border-amber-400 shadow-md">
                        <div
                            class="absolute -bottom-1 -right-1 bg-green-500 text-white text-xs rounded-full px-2 py-0.5">
                            New
                        </div>
                        @elseif($profile_picture)
                        <img src="{{ asset('storage/' . $profile_picture) }}?t={{ time() }}" alt="Profile Picture"
                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        @else
                        <div
                            class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center text-3xl text-amber-600 border-2 border-gray-200">
                            {{ substr($name, 0, 1) }}
                        </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                        <div class="flex items-center gap-3 flex-wrap">
                            <label class="cursor-pointer">
                                <span
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                                    Choose Photo
                                </span>
                                <input type="file" wire:model="new_profile_picture" accept="image/*" class="hidden">
                            </label>

                            @if($profile_picture)
                            <button type="button" wire:click="removeProfilePicture"
                                class="px-4 py-2 text-sm text-red-600 hover:text-red-700 hover:underline">
                                Remove
                            </button>
                            @endif
                        </div>
                        @error('new_profile_picture')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($new_profile_picture && !$errors->has('new_profile_picture'))
                        <p class="mt-1 text-xs text-green-600">✓ New photo selected</p>
                        <p class="mt-0.5 text-xs text-amber-600">⚠️ Click "Update Profile" to save permanently</p>
                        @endif
                        @if($uploadSuccess)
                        <p class="mt-1 text-xs text-green-600">✓ Profile picture updated successfully!</p>
                        @endif
                        <p class="mt-1 text-xs text-gray-400">Max 2MB • JPG, PNG, or GIF</p>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror"
                        placeholder="you@example.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Must be a valid, reachable email address</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="phone" maxlength="11"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('phone') border-red-500 @enderror"
                        placeholder="09123456789">
                    @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Philippine mobile number (exactly 11 digits, e.g.,
                        09123456789)</p>
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                <button wire:click="togglePasswordForm" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                    {{ $showPasswordForm ? 'Cancel' : 'Change Password' }}
                </button>
            </div>
            <p class="text-xs text-gray-500 mb-4">
                <a href="{{ route('livewire.auth.forgot-password') }}"
                    class="text-amber-600 hover:text-amber-700 hover:underline font-medium">
                    Forgot your current password? Reset it here →
                </a>
            </p>

            @if($showPasswordForm)
            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <div class="relative flex items-center" style="position: relative;" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" wire:model="current_password"
                            class="w-full px-4 py-2 pr-11 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('current_password') border-red-500 @enderror"
                            style="padding-right: 2.75rem;">
                        <button type="button" @click="show = !show"
                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; background: transparent; border: none;"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="relative flex items-center" style="position: relative;" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" wire:model="new_password"
                            class="w-full px-4 py-2 pr-11 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('new_password') border-red-500 @enderror"
                            style="padding-right: 2.75rem;">
                        <button type="button" @click="show = !show"
                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; background: transparent; border: none;"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <div class="relative flex items-center" style="position: relative;" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation"
                            class="w-full px-4 py-2 pr-11 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                            style="padding-right: 2.75rem;">
                        <button type="button" @click="show = !show"
                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; background: transparent; border: none;"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Password
                </button>
            </form>
            @else
            <p class="text-sm text-gray-500">Click "Change Password" to update your password.</p>
            @endif
        </div>
    </div>
</div>
