<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">👥 Manage Employees</h1>
                <p class="text-sm text-gray-500">View and manage your shop employees</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="createNew"
                    class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    + Add Employee
                </button>
                <button wire:click="toggleDeleted"
                    class="px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                    {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                </button>
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">✕</button>
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

            <!-- Branch Filter Dropdown -->
            <div class="sm:w-48">
                <select wire:model.live="selectedBranchId"
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
                {{ $editing ? '✏️ Edit Employee' : '➕ Add New Employee' }}
            </h2>
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" maxlength="11"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="09XXXXXXXXX">
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Role -->
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

                <!-- Branch -->
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

                <!-- Password Fields (For new employees) -->
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

                <!-- Password Reset Section (For editing employees) -->
                @if($editing)
                <div class="md:col-span-2 border-t border-gray-200 pt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-700">🔒 Password</span>
                        </div>
                        <button type="button" wire:click="toggleResetPassword"
                            class="px-3 py-1.5 text-xs bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition">
                            {{ $showResetPassword ? 'Cancel' : '🔄 Reset Password' }}
                        </button>
                    </div>

                    @if($showResetPassword)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <div class="relative" style="position: relative; height: 42px;"
                                    x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" wire:model="current_password"
                                        class="block h-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 pr-10"
                                        placeholder="Enter current password">
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
                                @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div></div>

                            <!-- New Password -->
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

                            <!-- Confirm New Password -->
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
                        <div class="flex gap-3 mt-3">
                            <button type="button" wire:click="updatePassword"
                                class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                                💾 Update Password
                            </button>
                            <button type="button" wire:click="toggleResetPassword"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Submit Buttons -->
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
                        $isSuspendedByAdmin = !$employee->user->is_active && $employee->is_active && !$isDeleted;
                        $isDeactivatedByOwner = !$employee->is_active && !$isDeleted && !$isSuspendedByAdmin;
                        $isActive = $employee->is_active && $employee->user->is_active && !$isDeleted;
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
                                    {{ $isDeleted ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $isSuspendedByAdmin ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $isDeactivatedByOwner ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $isActive ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $isDeleted ? 'Deleted' :
                                    ($isSuspendedByAdmin ? 'Suspended by Admin' :
                                    ($isDeactivatedByOwner ? 'Deactivated by Owner' :
                                    ($isActive ? 'Active' : 'Inactive'))) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($isDeleted)
                                <button wire:click="restore({{ $employee->id }})"
                                    class="text-green-600 hover:text-green-800 text-xs">
                                    Restore
                                </button>
                                @elseif($isSuspendedByAdmin)
                                <span class="text-xs text-red-500 font-medium">Suspended by Admin</span>
                                @else
                                <button wire:click="edit({{ $employee->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-xs">
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
                <span class="text-4xl block mb-2">👥</span>
                <p>No employees found.</p>
            </div>
            @endif
        </div>
    </div>
</div>