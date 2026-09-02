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
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search employees..."
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
                    <input type="password" wire:model="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" wire:model="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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