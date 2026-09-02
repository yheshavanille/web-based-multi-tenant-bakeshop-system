<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">👥 Manage Users</h1>
                <p class="text-sm text-gray-500">View and manage all registered users</p>
            </div>
            <a href="{{ route('livewire.admin.admin-dashboard') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Flash Messages -->
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

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button wire:click="setTab('active')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'active' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                🟢 Active Users
            </button>
            <button wire:click="setTab('soft_deleted')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'soft_deleted' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                🗑️ Soft Deleted
            </button>
            <button wire:click="setTab('permanently_deleted')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'permanently_deleted' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                💀 Permanently Deleted
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($activeTab !== 'permanently_deleted')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select wire:model.live="roleFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="all">All Roles</option>
                        <option value="customer">Customer</option>
                        <option value="owner">Owner</option>
                        <option value="employee">Employee</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                @endif

                @if($activeTab === 'active')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="all">All Status</option>
                        <option value="active">🟢 Active</option>
                        <option value="suspended">🔴 Suspended</option>
                        <option value="deactivated_by_owner">🟡 Deactivated by Owner</option>
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" wire:model.live="search" placeholder="Search by name or email..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Active / Soft Deleted Users Table -->
        @if($activeTab !== 'permanently_deleted')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">User</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Shop</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Role(s)</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Joined</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($users as $user)
                        @php
                        $employee = $user->employee;
                        $employeeStatus = null;
                        if ($employee) {
                        if ($employee->trashed()) {
                        $employeeStatus = '🗑️ Deleted by Employer';
                        } elseif (!$employee->is_active) {
                        $employeeStatus = '🟡 Deactivated by Owner';
                        }
                        }
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center text-xs font-bold text-amber-700">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <span
                                        class="font-medium text-gray-800 {{ $user->trashed() ? 'line-through text-gray-400' : '' }}">
                                        {{ $user->name }}
                                    </span>
                                    @if($user->trashed())
                                    <span
                                        class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">Deleted</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($user->shop)
                                <span class="text-sm font-medium text-gray-800">{{ $user->shop->shop_name }}</span>
                                @else
                                <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->getRoleNames() as $role)
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        @if($role === 'super_admin') bg-red-100 text-red-800
                                        @elseif($role === 'owner') bg-green-100 text-green-800
                                        @elseif($role === 'employee') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($role) }}
                                    </span>
                                    @empty
                                    <span class="text-xs text-gray-400">No role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->trashed())
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">
                                    Deleted
                                </span>
                                @elseif($employeeStatus)
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ strpos($employeeStatus, 'Deleted') !== false ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $employeeStatus }}
                                </span>
                                @elseif(isset($user->is_active) && $user->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    🟢 Active
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    🔴 Suspended
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $user->trashed() ? $user->deleted_at->format('M d, Y') : $user->created_at->format('M
                                d, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- ✅ VIEW DETAILS BUTTON -->
                                    <button wire:click="viewUserDetails({{ $user->id }})"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        👁️ View
                                    </button>

                                    @if($user->trashed())
                                    <button wire:click="restoreUser({{ $user->id }})"
                                        class="text-xs text-green-600 hover:text-green-800 font-medium">
                                        🔄 Restore
                                    </button>
                                    <button wire:click="forceDeleteUser({{ $user->id }})"
                                        onclick="confirm('Permanently delete this user? This cannot be undone.') || event.stopImmediatePropagation()"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        💀 Permanently Delete
                                    </button>
                                    @elseif($user->id !== auth()->id())
                                    <button wire:click="toggleUserStatus({{ $user->id }})"
                                        class="text-xs {{ isset($user->is_active) && $user->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }} font-medium">
                                        {{ isset($user->is_active) && $user->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})"
                                        onclick="confirm('Soft delete this user?') || event.stopImmediatePropagation()"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        🗑️ Delete
                                    </button>
                                    @else
                                    <span class="text-xs text-gray-400">You</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $users->links() }}
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <span class="text-4xl block mb-2">👤</span>
                <p>No users found.</p>
                <p class="text-sm text-gray-400">Try adjusting your filters.</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Permanently Deleted Users Table -->
        @if($activeTab === 'permanently_deleted')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($permanentlyDeletedUsers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">User</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Shop</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Roles</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Deleted By</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Deleted At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($permanentlyDeletedUsers as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $log->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->email }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->shop_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->roles ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->deleted_by ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $log->deleted_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $permanentlyDeletedUsers->links() }}
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <span class="text-4xl block mb-2">📭</span>
                <p>No permanently deleted users found.</p>
                <p class="text-sm text-gray-400">Users who are permanently deleted will appear here.</p>
            </div>
            @endif
        </div>
        @endif

        <!-- ✅ USER DETAILS MODAL -->
        @if($showUserModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeUserModal"></div>

            <div
                class="relative z-10 w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                <!-- Modal Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-xl font-bold text-amber-700">
                                {{ strtoupper(substr($selectedUser->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $selectedUser->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $selectedUser->email }}</p>
                            </div>
                        </div>
                        <button wire:click="closeUserModal" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">

                    <!-- User Info Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Full Name</p>
                            <p class="font-medium text-gray-800">{{ $selectedUser->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">{{ $selectedUser->email }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="font-medium text-gray-800">{{ $selectedUser->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Status</p>
                            @if($selectedUser->trashed())
                            <span class="text-sm font-medium text-red-600">🗑️ Deleted</span>
                            @elseif($selectedUser->is_active)
                            <span class="text-sm font-medium text-green-600">🟢 Active</span>
                            @else
                            <span class="text-sm font-medium text-red-600">🔴 Suspended</span>
                            @endif
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                            <p class="text-xs text-gray-500">Roles</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @forelse($selectedUser->getRoleNames() as $role)
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    @if($role === 'super_admin') bg-red-100 text-red-800
                                    @elseif($role === 'owner') bg-green-100 text-green-800
                                    @elseif($role === 'employee') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($role) }}
                                </span>
                                @empty
                                <span class="text-xs text-gray-400">No roles assigned</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                            <p class="text-xs text-gray-500">Shop</p>
                            @if($selectedUser->shop)
                            <p class="font-medium text-gray-800">{{ $selectedUser->shop->shop_name }}</p>
                            <p class="text-xs text-gray-400">{{ $selectedUser->shop->address ?? 'No address' }}</p>
                            @else
                            <p class="text-sm text-gray-400">No shop assigned</p>
                            @endif
                        </div>

                        @if($selectedUser->employee)
                        <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                            <p class="text-xs text-gray-500">Employee Details</p>
                            <div class="grid grid-cols-2 gap-2 mt-1">
                                <div>
                                    <p class="text-xs text-gray-400">Role</p>
                                    <p class="text-sm font-medium text-gray-800">{{
                                        ucfirst($selectedUser->employee->role ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Branch</p>
                                    <p class="text-sm font-medium text-gray-800">{{
                                        $selectedUser->employee->branch->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Employee Status</p>
                                    @if($selectedUser->employee->trashed())
                                    <span class="text-sm font-medium text-red-600">🗑️ Deleted by Employer</span>
                                    @elseif($selectedUser->employee->is_active)
                                    <span class="text-sm font-medium text-green-600">🟢 Active</span>
                                    @else
                                    <span class="text-sm font-medium text-yellow-600">🟡 Disabled</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Joined</p>
                                    <p class="text-sm font-medium text-gray-800">{{
                                        $selectedUser->employee->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Account Created</p>
                            <p class="font-medium text-gray-800">{{ $selectedUser->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        @if($selectedUser->trashed())
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Deleted At</p>
                            <p class="font-medium text-red-600">{{ $selectedUser->deleted_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0 flex justify-end">
                    <button wire:click="closeUserModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Close
                    </button>
                </div>

            </div>
        </div>
        @endif

    </div>
</div>