<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">👥 Employee Activities</h1>
                <p class="text-sm text-gray-500">Track what your employees have been doing</p>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                        style="position: absolute;">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="search" placeholder="Search description..."
                        class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    @if(!empty($search))
                    <button wire:click="clearSearch"
                        class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                        style="position: absolute;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    @endif
                </div>

                <!-- Employee Filter -->
                <div>
                    <select wire:model.live="selectedEmployee"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                        <option value="all">👥 All Employees</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->user->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <select wire:model.live="selectedAction"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-white">
                        <option value="all">🔎 All Actions</option>
                        <option value="password_changed_self">Changed own password</option>
                        <option value="password_changed_by_owner">Password reset by owner</option>
                        <option value="profile_updated">Updated own profile</option>
                        <option value="employee_created">Account created by owner</option>
                    </select>
                </div>
            </div>

            @if($selectedEmployee !== 'all' || $selectedAction !== 'all' || !empty($search))
            <div class="mt-3 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Showing <span class="font-medium text-amber-600">{{ $activities->count() }}</span> result(s)
                </p>
                <button wire:click="clearFilters" class="text-xs text-amber-600 hover:text-amber-700 font-medium">
                    Clear all filters
                </button>
            </div>
            @endif
        </div>

        <!-- Activities List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($activities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Employee</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Description</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($activities as $activity)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
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
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $activity->employee->user->name ?? 'N/A'
                                            }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity->employee->branch->name ?? 'N/A'
                                            }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-xs px-2 py-1 rounded-full
                                    {{ $activity->action === 'password_changed_self' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $activity->action === 'password_changed_by_owner' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $activity->action === 'profile_updated' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $activity->action === 'employee_created' ? 'bg-amber-100 text-amber-800' : '' }}">
                                    {{ $activity->action_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $activity->description }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">
                                {{ $activity->created_at->diffForHumans() }}
                                <span class="block text-gray-300">{{ $activity->created_at->format('M d, Y h:i A')
                                    }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <span class="text-4xl block mb-2">📭</span>
                <p>No activities found.</p>
                @if($selectedEmployee !== 'all' || $selectedAction !== 'all' || !empty($search))
                <p class="text-xs text-gray-400 mt-1">Try clearing your filters.</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
