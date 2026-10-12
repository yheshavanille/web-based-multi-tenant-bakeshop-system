<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-slate-100">Manage Users</h1>
                <p class="text-sm text-gray-500 dark:text-slate-400">View and manage all registered users</p>
            </div>
            <a href="{{ route('livewire.admin.admin-dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 dark:border-green-800 dark:text-green-200 border border-green-200 text-green-700 dark:text-green-300 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200 border border-red-200 text-red-700 dark:text-red-300 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Tabs -->
        <div class="flex flex-wrap gap-1 border-b border-gray-200 dark:border-slate-700 mb-6">
            <button wire:click="setTab('active')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'active' ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300' }}">
                Active Users
            </button>
            <button wire:click="setTab('soft_deleted')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'soft_deleted' ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300' }}">
                Archived
            </button>
            <button wire:click="setTab('permanently_deleted')"
                class="px-4 py-2 text-sm font-medium {{ $activeTab === 'permanently_deleted' ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300' }}">
                Permanently Deleted
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($activeTab !== 'permanently_deleted')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Role</label>
                    <div class="relative">
                        <select wire:model.live="roleFilter"
                            class="appearance-none w-full px-3 py-2 pr-9 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white dark:bg-slate-700 cursor-pointer text-gray-900 dark:text-slate-100 dark:border-slate-600 dark:placeholder-slate-400">
                            <option value="all" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">All Roles</option>
                            <option value="customer" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Customer</option>
                            <option value="owner" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Owner</option>
                            <option value="employee" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Employee</option>
                            <option value="super_admin" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Super Admin</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif

                @if($activeTab === 'active')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Status</label>
                    <div class="relative">
                        <select wire:model.live="statusFilter"
                            class="appearance-none w-full px-3 py-2 pr-9 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white dark:bg-slate-700 cursor-pointer text-gray-900 dark:text-slate-100 dark:border-slate-600 dark:placeholder-slate-400">
                            <option value="all" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">All Status</option>
                            <option value="active" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Active</option>
                            <option value="suspended" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Suspended</option>
                            <option value="deactivated_by_owner" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Deactivated by Owner</option>
                            <option value="suspended_by_admin" class="bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100">Suspended by Super Admin</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Search</label>
                    <input type="text" wire:model.live="search" placeholder="Search by name or email..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100 dark:border-slate-600 dark:placeholder-slate-400">
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition text-sm font-medium">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Active / Soft Deleted Users Table -->
        @if($activeTab !== 'permanently_deleted')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">User</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Shop</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Role(s)</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Last Login</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Joined</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @foreach($users as $user)
                        @php
                        $employee = $user->employee;
                        $employeeStatus = null;
                        if ($employee) {
                        if ($employee->trashed()) {
                        $employeeStatus = 'Deleted by Employer';
                        } elseif (!$employee->is_active) {
                        if ($employee->deactivated_by === 'super_admin') {
                        $employeeStatus = 'Suspended by Super Admin';
                        } else {
                        $employeeStatus = 'Deactivated by Owner';
                        }
                        }
                        }
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}?v={{ $user->updated_at?->timestamp }}"
                                        alt="{{ $user->name }}"
                                        class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-slate-700 flex-shrink-0">
                                    @else
                                    <div
                                        class="w-8 h-8 bg-amber-100 dark:bg-amber-900/40 rounded-full flex items-center justify-center text-xs font-bold text-amber-700 flex-shrink-0">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    @endif
                                    <span
                                        class="font-medium text-gray-800 dark:text-slate-100 {{ $user->trashed() ? 'line-through text-gray-400 dark:text-slate-500' : '' }}">
                                        {{ $user->name }}
                                    </span>
                                    @if($user->trashed())
                                    <span
                                        class="text-xs bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200 px-2 py-0.5 rounded-full">Deleted</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">
                                @if($user->shop)
                                <span class="text-sm font-medium text-gray-800 dark:text-slate-100">{{ $user->shop->shop_name }}</span>
                                @else
                                <span class="text-xs text-gray-400 dark:text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->getRoleNames() as $role)
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        @if($role === 'super_admin') bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200
                                        @elseif($role === 'owner') bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200
                                        @elseif($role === 'employee') bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200
                                        @else bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100 @endif">
                                        {{ ucfirst($role) }}
                                    </span>
                                    @empty
                                    <span class="text-xs text-gray-400 dark:text-slate-500">No role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->trashed())
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400">
                                    Deleted
                                </span>
                                @elseif($employeeStatus)
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if(strpos($employeeStatus, 'Suspended by Super Admin') !== false) bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200
                                    @elseif(strpos($employeeStatus, 'Deleted by Employer') !== false) bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200
                                    @else bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-200 @endif">
                                    {{ $employeeStatus }}
                                </span>
                                @elseif(isset($user->is_active) && $user->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200">
                                    Active
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200">
                                    Suspended
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->last_login_at)
                                <div>
                                    <p class="text-xs text-gray-700 dark:text-slate-300">{{ $user->last_login_at->diffForHumans() }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-slate-500">{{ $user->last_login_at->format('M d, Y h:i A')
                                        }}</p>
                                </div>
                                @else
                                <span class="text-xs text-gray-400 dark:text-slate-500 italic">Never</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-400">
                                {{ $user->trashed() ? $user->deleted_at->format('M d, Y') : $user->created_at->format('M
                                d, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button wire:click="viewUserDetails({{ $user->id }})"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        View
                                    </button>

                                    @if($user->trashed())
                                    <button wire:click="restoreUser({{ $user->id }})"
                                        class="text-xs text-green-600 hover:text-green-800 font-medium">
                                        Restore
                                    </button>
                                    <button wire:click="forceDeleteUser({{ $user->id }})"
                                        onclick="confirm('Permanently delete this user? This cannot be undone.') || event.stopImmediatePropagation()"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Permanently Delete
                                    </button>
                                    @elseif($user->id !== auth()->id())
                                    @if(isset($user->is_active) && $user->is_active)
                                    {{-- opens reason modal --}}
                                    <button wire:click="openSuspendModal({{ $user->id }})"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Suspend
                                    </button>
                                    @else
                                    <button wire:click="activateUser({{ $user->id }})"
                                        class="text-xs text-green-600 hover:text-green-800 font-medium">
                                        Activate
                                    </button>
                                    @endif

                                    {{-- opens archive reason modal --}}
                                    <button wire:click="openArchiveModal({{ $user->id }})"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Archive
                                    </button>
                                    @else
                                    <span class="text-xs text-gray-400 dark:text-slate-500">You</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700">
                {{ $users->links() }}
            </div>
            @else
            <div class="text-center py-12 text-gray-500 dark:text-slate-400">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p>No users found.</p>
                <p class="text-sm text-gray-400 dark:text-slate-500">Try adjusting your filters.</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Permanently Deleted Users Table -->
        @if($activeTab === 'permanently_deleted')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            @if($permanentlyDeletedUsers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">User</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Shop</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Roles</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Deleted By</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-slate-300">Deleted At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @foreach($permanentlyDeletedUsers as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">{{ $log->name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $log->email }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $log->shop_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $log->roles ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $log->deleted_by ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-gray-400 dark:text-slate-500 text-xs">{{ $log->deleted_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700">
                {{ $permanentlyDeletedUsers->links() }}
            </div>
            @else
            <div class="text-center py-12 text-gray-500 dark:text-slate-400">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                    </path>
                </svg>
                <p>No permanently deleted users found.</p>
                <p class="text-sm text-gray-400 dark:text-slate-500">Users who are permanently deleted will appear here.</p>
            </div>
            @endif
        </div>
        @endif

        <!-- USER DETAILS MODAL -->
        @if($showUserModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeUserModal"></div>

            <div
                class="relative z-10 w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                <div
                    class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-blue-50 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-200 flex-shrink-0">
                    <div class="flex flex-wrap items-center gap-3">
                        @if($selectedUser->profile_picture)
                        <img src="{{ asset('storage/' . $selectedUser->profile_picture) }}?v={{ $selectedUser->updated_at?->timestamp }}"
                            alt="{{ $selectedUser->name }}"
                            class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-slate-700 flex-shrink-0">
                        @else
                        <div
                            class="w-12 h-12 bg-amber-100 dark:bg-amber-900/40 rounded-full flex items-center justify-center text-lg sm:text-xl font-bold text-amber-700 flex-shrink-0">
                            {{ strtoupper(substr($selectedUser->name ?? 'U', 0, 2)) }}
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-slate-100 truncate">{{ $selectedUser->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400 truncate">{{ $selectedUser->email }}</p>
                        </div>

                        <button wire:click="closeUserModal"
                            class="text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 transition flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Full Name</p>
                            <p class="font-medium text-gray-800 dark:text-slate-100">{{ $selectedUser->name }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Email</p>
                            <p class="font-medium text-gray-800 dark:text-slate-100">{{ $selectedUser->email }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Phone</p>
                            <p class="font-medium text-gray-800 dark:text-slate-100">{{ $selectedUser->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Status</p>
                            @if($selectedUser->trashed())
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Deleted
                            </span>
                            @elseif($selectedUser->is_active)
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                    </path>
                                </svg>
                                Suspended
                            </span>
                            @endif
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3 sm:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Roles</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @forelse($selectedUser->getRoleNames() as $role)
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    @if($role === 'super_admin') bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200
                                    @elseif($role === 'owner') bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200
                                    @elseif($role === 'employee') bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200
                                    @else bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-100 @endif">
                                    {{ ucfirst($role) }}
                                </span>
                                @empty
                                <span class="text-xs text-gray-400 dark:text-slate-500">No roles assigned</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3 sm:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Shop</p>
                            @if($selectedUser->shop)
                            <p class="font-medium text-gray-800 dark:text-slate-100">{{ $selectedUser->shop->shop_name }}</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500">{{ $selectedUser->shop->address ?? 'No address' }}</p>
                            @else
                            <p class="text-sm text-gray-400 dark:text-slate-500">No shop assigned</p>
                            @endif
                        </div>

                        @if($selectedUser->employee)
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3 sm:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Employee Details</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                                <div>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">Role</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{
                                        ucfirst($selectedUser->employee->role ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">Branch</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{
                                        $selectedUser->employee->branch->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">Employee Status</p>
                                    @if($selectedUser->employee->trashed())
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Deleted by Employer
                                    </span>
                                    @elseif($selectedUser->employee->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Active
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-yellow-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Disabled
                                    </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">Joined</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{
                                        $selectedUser->employee->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3 sm:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Last Login</p>
                            @if($selectedUser->last_login_at)
                            <div class="flex flex-wrap items-center justify-between gap-2 mt-1">
                                <p class="text-sm font-medium text-gray-800 dark:text-slate-100">{{ $selectedUser->last_login_at->format('M
                                    d, Y h:i A') }}</p>
                                <span class="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded-full">
                                    {{ $selectedUser->last_login_at->diffForHumans() }}
                                </span>
                            </div>
                            @else
                            <p class="text-sm text-gray-400 dark:text-slate-500 italic mt-1">This user has never logged in.</p>
                            @endif
                        </div>

                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Account Created</p>
                            <p class="font-medium text-gray-800 dark:text-slate-100">{{ $selectedUser->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        @if($selectedUser->trashed())
                        <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Deleted At</p>
                            <p class="font-medium text-red-600">{{ $selectedUser->deleted_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex-shrink-0 flex justify-end">
                    <button wire:click="closeUserModal"
                        class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition text-sm font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- SUSPEND REASON MODAL -->
        @if($showSuspendModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeSuspendModal"></div>

            <div class="relative z-10 w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-red-50 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200">
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                            </path>
                        </svg>
                        Suspend User
                    </h3>
                    <p class="text-xs text-red-600 mt-0.5">Suspending "{{ $actionUserName }}". They will be notified
                        with this reason.</p>
                </div>

                <div class="px-4 sm:px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">
                        Reason for suspension <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mb-2">
                        This will be sent to the user via notification and email.
                    </p>
                    <textarea wire:model="actionReason" rows="4" maxlength="500"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('actionReason') border-red-500 @enderror bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100 dark:border-slate-600 dark:placeholder-slate-400"
                        placeholder="e.g. Violation of platform policy, suspicious activity..."></textarea>
                    @error('actionReason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400 dark:text-slate-500 text-right">{{ strlen($actionReason) }}/500</p>
                </div>

                <div class="px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex justify-end gap-2">
                    <button wire:click="closeSuspendModal"
                        class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition text-sm font-medium">
                        Cancel
                    </button>
                    <button wire:click="confirmSuspend" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-sm disabled:opacity-60">
                        Confirm Suspend
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- ARCHIVE REASON MODAL -->
        @if($showArchiveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeArchiveModal"></div>

            <div class="relative z-10 w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-red-50 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200">
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                        Archive User
                    </h3>
                    <p class="text-xs text-red-600 mt-0.5">Archiving "{{ $actionUserName }}". They will be notified with
                        this reason.</p>
                </div>

                <div class="px-4 sm:px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">
                        Reason for archiving <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mb-2">
                        This will be sent to the user via notification and email. Their data will be preserved.
                    </p>
                    <textarea wire:model="actionReason" rows="4" maxlength="500"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('actionReason') border-red-500 @enderror bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100 dark:border-slate-600 dark:placeholder-slate-400"
                        placeholder="e.g. Duplicate account, inactive for a long time..."></textarea>
                    @error('actionReason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400 dark:text-slate-500 text-right">{{ strlen($actionReason) }}/500</p>
                </div>

                <div class="px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 flex justify-end gap-2">
                    <button wire:click="closeArchiveModal"
                        class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition text-sm font-medium">
                        Cancel
                    </button>
                    <button wire:click="confirmArchive" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium shadow-sm disabled:opacity-60">
                        Confirm Archive
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>