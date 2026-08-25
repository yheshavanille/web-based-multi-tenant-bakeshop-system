<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">👥 Manage Employees</h1>
                <p class="text-sm text-gray-500">Add and manage your branch staff</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="createNew"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Employee
                </button>
                <button wire:click="toggleDeleted"
                    class="px-4 py-2 {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                </button>
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search employees by name or email..."
                    class="w-full pl-10 pr-10 h-10 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500">
                Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
                <span class="text-gray-400">({{ $employees->count() }} found)</span>
            </p>
            @endif
        </div>

        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Add/Edit Form -->
        @if($showForm)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $editing ? '✏️ Edit Employee' : '➕ Add New Employee' }}
            </h2>

            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="Enter full name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" wire:model="email" placeholder="Enter email address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if(!$editing)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span
                            class="text-red-500">*</span></label>
                    <input type="password" wire:model="password" placeholder="Enter password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span
                            class="text-red-500">*</span></label>
                    <input type="password" wire:model="password_confirmation" placeholder="Confirm password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role <span
                            class="text-red-500">*</span></label>
                    <select wire:model="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('role') border-red-500 @enderror">
                        <option value="">Select role</option>
                        <option value="order_manager">📋 Order Manager</option>
                        <option value="inventory_manager">📦 Inventory Manager</option>
                    </select>
                    @error('role')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span
                            class="text-red-500">*</span></label>
                    <select wire:model="branch_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('branch_id') border-red-500 @enderror">
                        <option value="">Select branch</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                        {{ $editing ? 'Update Employee' : 'Save Employee' }}
                    </button>
                    <button type="button" wire:click="cancel"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Employee List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Employee</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Role</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($employees as $employee)
                        @php
                        $userDeleted = $employee->user && $employee->user->trashed();
                        $userSuspended = $employee->user && !$employee->user->trashed() && !$employee->user->is_active;
                        $userMissing = !$employee->user;
                        $employeeDeleted = $employee->trashed();
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div>
                                    <p
                                        class="font-medium text-gray-800 {{ ($userDeleted || $userMissing || $employeeDeleted) ? 'line-through text-gray-400' : '' }}">
                                        {{ $employee->user?->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $employee->user?->email ?? 'No email available'
                                        }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $employee->role === 'order_manager' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $employee->role === 'order_manager' ? '📋 Order Manager' : '📦 Inventory Manager'
                                    }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                📍 {{ $employee->branch->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($userDeleted)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    🔴 Account Deleted by Super Admin
                                </span>
                                @elseif($userSuspended)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    🟡 Account Suspended by Admin
                                </span>
                                @elseif($userMissing)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    ⚠️ Account Missing
                                </span>
                                @elseif($employeeDeleted)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    🗑️ Deleted
                                </span>
                                @elseif($employee->is_active)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    🟢 Active
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    🔴 Inactive
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-1">
                                @if($userDeleted || $userMissing || $userSuspended)
                                <!-- User deleted or suspended - No actions -->
                                <span class="text-xs text-gray-400">—</span>
                                @elseif($employeeDeleted)
                                <!-- Employee soft deleted - Restore available -->
                                <button wire:click="restore({{ $employee->id }})"
                                    class="px-3 py-1 text-xs font-medium text-green-600 hover:bg-green-50 rounded-lg transition">
                                    Restore
                                </button>
                                @else
                                <!-- Active employee - Full actions -->
                                <button wire:click="edit({{ $employee->id }})"
                                    class="px-3 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    Edit
                                </button>
                                <button wire:click="toggleStatus({{ $employee->id }})"
                                    class="px-3 py-1 text-xs font-medium text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                    {{ $employee->is_active ? 'Disable' : 'Enable' }}
                                </button>
                                <button wire:click="delete({{ $employee->id }})"
                                    onclick="confirm('Delete this employee?') || event.stopImmediatePropagation()"
                                    class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                                    Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">👥</span>
                                    <p>{{ $showDeleted ? 'No deleted employees found.' : 'No employees added yet.' }}
                                    </p>
                                    @if(!$showDeleted)
                                    <p class="text-xs text-gray-400">Click "Add Employee" to get started.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>