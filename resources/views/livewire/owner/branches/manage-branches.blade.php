<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manage Branches</h1>
                <p class="text-sm text-gray-500">Manage your shop branches. Add new locations, update contact info, and
                    toggle active status.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" wire:click="createNewBranch"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Branch
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
                <input type="text" wire:model.live="search"
                    placeholder="Search branches by name, address, or contact..."
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
                <span class="text-gray-400">({{ $branches->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('message') }}
        </div>
        @endif

        <!-- Branch List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Branch Name</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Address</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Contact</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($branches as $branch)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $branch->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $branch->address }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $branch->contact_number ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="toggleStatus({{ $branch->id }})"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                        {{ $branch->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                    <button type="button" wire:click="editBranch({{ $branch->id }})"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border border-blue-600 bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="deleteBranch({{ $branch->id }})"
                                        onclick="confirm('Are you sure you want to delete this branch?') || event.stopImmediatePropagation()"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border border-red-600 bg-red-50 text-red-700 hover:bg-red-100 transition">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="text-4xl mb-2">🏪</span>
                                    @if(!empty($search))
                                    <p class="font-medium text-gray-600">No branches found matching "<span
                                            class="text-amber-600">{{ $search }}</span>"</p>
                                    <p class="text-sm text-gray-400">Try adjusting your search.</p>
                                    @else
                                    <p class="font-medium text-gray-600">No branches yet</p>
                                    <p class="text-sm text-gray-400">Click "Add New Branch" to create your first branch.
                                    </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create/Edit Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ $editing ? '✏️ Edit Branch' : '➕ Create New Branch' }}
                </h3>

                <form wire:submit.prevent="saveBranch" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Branch Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Branch Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.defer="name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror"
                            placeholder="Enter branch name">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Contact Number
                        </label>
                        <input type="text" wire:model.defer="contact_number" maxlength="11"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('contact_number') border-red-500 @enderror"
                            placeholder="09123456789">
                        @error('contact_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Exactly 11 digits, starting with 09 (e.g., 09123456789)
                        </p>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model.defer="address" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('address') border-red-500 @enderror"
                            placeholder="Enter branch address"></textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.defer="is_active" id="branch-active"
                                class="w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm text-gray-700">Branch is active</span>
                        </label>
                    </div>

                    <!-- Form Buttons -->
                    <div class="md:col-span-2 flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow-md">
                            {{ $editing ? 'Update Branch' : 'Save Branch' }}
                        </button>
                        @if($editing)
                        <button type="button" wire:click="resetForm"
                            class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-medium">
                            Cancel
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>