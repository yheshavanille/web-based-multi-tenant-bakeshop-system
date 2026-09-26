<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Employees</h1>
                <p class="text-sm text-gray-500">View and manage your shop employees</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="createNew"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Employee
                </button>
                <button wire:click="toggleDeleted"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                    @if($showDeleted)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Show Active
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Show Deleted
                    @endif
                </button>
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        <!-- Search Bar & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
            <div class="relative flex-1">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search employees..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                    style="position: absolute;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>

            <div class="sm:w-48">
                <select wire:model.live="selectedBranchId"
                    style="appearance: auto; -webkit-appearance: auto; -moz-appearance: auto; background-image: none;"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Employee Form -->
        @if($showForm)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $editing ? 'Edit Employee' : 'Add New Employee' }}
            </h2>
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2 pb-4 border-b border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Profile Picture</label>
                    <div class="flex items-center gap-6">
                        <div class="relative flex-shrink-0">
                            @if($temp_profile_picture_preview)
                            <img src="{{ $temp_profile_picture_preview }}" alt="Preview"
                                class="w-20 h-20 rounded-full object-cover border-2 border-amber-400 shadow-md flex-shrink-0">
                            <div
                                class="absolute -bottom-1 -right-1 bg-green-500 text-white text-xs rounded-full px-2 py-0.5">
                                New
                            </div>
                            @elseif($existing_profile_picture && !$removeImage)
                            <img src="{{ asset('storage/' . $existing_profile_picture) }}?v={{ time() }}"
                                alt="Profile Picture"
                                class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">
                            @else
                            <div
                                class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center text-3xl text-amber-600 border-2 border-gray-200 flex-shrink-0">
                                {{ strtoupper(substr($name ?: 'E', 0, 1)) }}
                            </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <label class="cursor-pointer">
                                    <span
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                                        Choose Photo
                                    </span>
                                    <input type="file" wire:model="new_profile_picture" accept="image/*" class="hidden">
                                </label>

                                @if(($existing_profile_picture || $temp_profile_picture_preview) && !$removeImage)
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
                            <p class="mt-1 text-xs text-green-600 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                New photo selected
                            </p>
                            <p class="mt-0.5 text-xs text-amber-600">Click "{{ $editing ? 'Update Employee' : 'Save
                                Employee' }}" to save</p>
                            @endif
                            @if($removeImage)
                            <p class="mt-1 text-xs text-red-600 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                Profile picture will be removed on save
                            </p>
                            @endif
                            <p class="mt-1 text-xs text-gray-400">Max 2MB • JPG, PNG, or GIF</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" maxlength="11"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="09XXXXXXXXX">
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select wire:model="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Role</option>
                        <option value="order_manager">Order Manager</option>
                        <option value="inventory_manager">Inventory Manager</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select wire:model="branch_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                @if(!$editing)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative" style="position: relative; height: 42px;" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" wire:model="password"
                            class="block h-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-10"
                            placeholder="Enter password">
                        <button type="button" @click="show = !show"
                            style="position: absolute; top: 0; right: 0; width: 40px; height: 42px;"
                            class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="relative" style="position: relative; height: 42px;" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" wire:model="password_confirmation"
                            class="block h-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-10"
                            placeholder="Confirm password">
                        <button type="button" @click="show = !show"
                            style="position: absolute; top: 0; right: 0; width: 40px; height: 42px;"
                            class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @if($editing)
                <div class="md:col-span-2 border-t border-gray-200 pt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Password</span>
                        </div>
                        <button type="button" wire:click="toggleResetPassword"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition">
                            @if($showResetPassword)
                            Cancel
                            @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset Password
                            @endif
                        </button>
                    </div>

                    @if($showResetPassword)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 mb-3">
                            Set a new password for this employee. They will use it on their next login.
                            Leave these fields blank to keep the current password.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <div class="relative" style="position: relative; height: 42px;"
                                    x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" wire:model="new_password"
                                        class="block h-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 pr-10"
                                        placeholder="Enter new password">
                                    <button type="button" @click="show = !show"
                                        style="position: absolute; top: 0; right: 0; width: 40px; height: 42px;"
                                        class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @error('new_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <div class="relative" style="position: relative; height: 42px;"
                                    x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation"
                                        class="block h-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 pr-10"
                                        placeholder="Confirm new password">
                                    <button type="button" @click="show = !show"
                                        style="position: absolute; top: 0; right: 0; width: 40px; height: 42px;"
                                        class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <div class="md:col-span-2 flex gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        {{ $editing ? 'Update Employee' : 'Save Employee' }}
                    </button>
                    <button type="button" wire:click="cancel"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- RECENT EMPLOYEE ACTIVITIES -->
        @if(isset($recentEmployeeActivities) && $recentEmployeeActivities->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-semibold text-gray-800">Recent Employee Activities</h2>
                    <span class="text-xs text-gray-500">Last 5</span>
                </div>
                <a href="{{ route('livewire.owner.employee-activities') }}"
                    class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">
                    View All
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

            <div class="space-y-2">
                @foreach($recentEmployeeActivities as $activity)
                <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-lg border border-gray-100">
                    @php
                    $pic = $activity->employee->user->profile_picture ?? null;
                    @endphp
                    @if($pic)
                    <img src="{{ asset('storage/' . $pic) }}"
                        class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                    @else
                    <div
                        class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                        {{ strtoupper(substr($activity->employee->user->name ?? 'E', 0, 2)) }}
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-700 truncate">{{ $activity->description }}</p>
                    </div>
                    <p class="text-xs text-gray-400 flex-shrink-0">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Employees Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($employees->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Name</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Phone</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Role</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($employees as $employee)
                        @php
                        $isDeleted = $employee->trashed();
                        $statusLabel = $employee->getStatusLabel();
                        $statusColor = $employee->getStatusColor();

                        $isSuspendedByAdmin = !$isDeleted
                        && !$employee->user?->is_active
                        && $employee->deactivated_by === 'super_admin';
                        $isDeactivatedByOwner = !$isDeleted
                        && !$employee->is_active
                        && $employee->deactivated_by === 'owner';
                        $isActive = !$isDeleted
                        && $employee->is_active
                        && ($employee->user?->is_active ?? false);
                        @endphp
                        <tr class="{{ $isDeleted ? 'opacity-60' : '' }}">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $employee->user->name ?? 'N/A' }}
                                @if($isDeleted)
                                <span class="text-xs text-red-500 ml-2">(Deleted)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $employee->user->email ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $employee->user->phone ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $employee->branch->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                    {{ $employee->role === 'order_manager' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $employee->role === 'inventory_manager' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $employee->role)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($statusColor === 'red') bg-red-100 text-red-800
                                    @elseif($statusColor === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($statusColor === 'green') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="viewDetails({{ $employee->id }})"
                                    class="text-gray-600 hover:text-gray-800 text-xs font-medium">
                                    View Details
                                </button>

                                @if($isDeleted)
                                <button wire:click="restore({{ $employee->id }})"
                                    class="text-green-600 hover:text-green-800 text-xs ml-2">
                                    Restore
                                </button>
                                @elseif($isSuspendedByAdmin)
                                <span class="text-xs text-red-500 font-medium ml-2">Suspended by Admin</span>
                                @else
                                <button wire:click="edit({{ $employee->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-xs ml-2">
                                    Edit
                                </button>
                                <button wire:click="toggleStatus({{ $employee->id }})"
                                    class="text-amber-600 hover:text-amber-800 text-xs ml-2">
                                    {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button wire:click="delete({{ $employee->id }})"
                                    onclick="confirm('Delete this employee?') || event.stopImmediatePropagation()"
                                    class="text-red-600 hover:text-red-800 text-xs ml-2">
                                    Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <p>No employees found.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- EMPLOYEE DETAILS MODAL --}}
    @if($showDetailsModal && $selectedEmployee)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeDetailsModal"></div>

        <div
            class="relative z-10 w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @php
                        $modalPic = $selectedEmployee->user->profile_picture ?? null;
                        $modalName = $selectedEmployee->user->name ?? 'Unknown Employee';
                        @endphp

                        @if($modalPic)
                        <img src="{{ asset('storage/' . $modalPic) }}?v={{ time() }}" alt="{{ $modalName }}"
                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm flex-shrink-0">
                        @else
                        <div
                            class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                            {{ strtoupper(substr($modalName, 0, 2)) }}
                        </div>
                        @endif

                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $modalName }}</h3>
                            <p class="text-sm text-gray-500">{{ $selectedEmployee->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                <!-- Basic Info Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Full Name</p>
                        <p class="font-medium text-gray-800">{{ $selectedEmployee->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="font-medium text-gray-800">{{ $selectedEmployee->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Phone</p>
                        <p class="font-medium text-gray-800">{{ $selectedEmployee->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500">Role</p>
                        <p class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $selectedEmployee->role))
                            }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="font-medium text-gray-800">{{ $selectedEmployee->branch->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-xs text-gray-500">Status</p>
                        @php
                        $modalStatusLabel = $selectedEmployee->getStatusLabel();
                        $modalStatusColor = $selectedEmployee->getStatusColor();
                        @endphp
                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full
                            @if($modalStatusColor === 'red') bg-red-100 text-red-800
                            @elseif($modalStatusColor === 'yellow') bg-yellow-100 text-yellow-800
                            @elseif($modalStatusColor === 'green') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $modalStatusLabel }}
                        </span>
                    </div>
                </div>

                {{-- Moderation Info --}}
                @if($moderationInfo)
                @if($moderationInfo['type'] === 'user_suspended')
                <div class="bg-red-50 rounded-lg p-4 border-2 border-red-200">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-800">Suspended by Super Admin</p>
                            <p class="text-xs text-red-600 mt-0.5">
                                {{ $moderationInfo['when']->format('M d, Y h:i A') }} ({{
                                $moderationInfo['when']->diffForHumans() }})
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                <span class="font-medium">By:</span> {{ $moderationInfo['by'] }}
                            </p>
                        </div>
                    </div>

                    @if(!empty($moderationInfo['reason']))
                    <div class="mt-3 pt-3 border-t border-red-200">
                        <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-1">Reason</p>
                        <p class="text-sm text-gray-800 italic">"{{ $moderationInfo['reason'] }}"</p>
                    </div>
                    @endif
                </div>
                @elseif($moderationInfo['type'] === 'user_archived')
                <div class="bg-red-50 rounded-lg p-4 border-2 border-red-200">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-800">Archived by Super Admin</p>
                            <p class="text-xs text-red-600 mt-0.5">
                                {{ $moderationInfo['when']->format('M d, Y h:i A') }} ({{
                                $moderationInfo['when']->diffForHumans() }})
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                <span class="font-medium">By:</span> {{ $moderationInfo['by'] }}
                            </p>
                        </div>
                    </div>

                    @if(!empty($moderationInfo['reason']))
                    <div class="mt-3 pt-3 border-t border-red-200">
                        <p
                            class="text-xs font-bold text-red-700 uppercase tracking-wider mb-1 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            Reason
                        </p>
                        <p class="text-sm text-gray-800 italic">"{{ $moderationInfo['reason'] }}"</p>
                    </div>
                    @endif
                </div>
                @elseif($moderationInfo['type'] === 'user_restored')
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-green-800">Account Restored</p>
                            <p class="text-xs text-green-600 mt-0.5">
                                {{ $moderationInfo['when']->format('M d, Y h:i A') }} ({{
                                $moderationInfo['when']->diffForHumans() }})
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                <span class="font-medium">By:</span> {{ $moderationInfo['by'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif
                @elseif($selectedEmployee->deactivated_by === 'owner' && !$selectedEmployee->is_active)
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-yellow-800">Deactivated by You</p>
                            <p class="text-xs text-gray-600 mt-1">
                                You deactivated this employee from your shop. You can reactivate them anytime.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                <button wire:click="closeDetailsModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif
    {{-- Auto-scroll to top on create/edit --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</div>