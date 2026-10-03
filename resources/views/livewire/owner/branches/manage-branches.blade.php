<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('livewire.owner.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manage Branches</h1>
                    <p class="text-sm text-gray-500">{{ $branches->count() }} branches</p>
                </div>
            </div>

        </div>

        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg shadow-sm">
            {{ session('message') }}
        </div>
        @endif

        <div class="mb-4">
            <input type="text" wire:model.live="search" placeholder="Search branches..."
                class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-6">
                    <h2 class="text-base font-semibold text-gray-800 mb-4">
                        {{ $editing ? 'Edit Branch' : 'Create New Branch' }}
                    </h2>
                    <form wire:submit.prevent="saveBranch" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch Name *</label>
                            <input type="text" wire:model="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <textarea wire:model="address" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('address') border-red-500 @enderror"></textarea>
                            @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <input type="text" wire:model="contact_number" maxlength="11"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('contact_number') border-red-500 @enderror">
                            @error('contact_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="is_active" id="is_active"
                                class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium shadow-sm">
                                {{ $editing ? 'Update' : 'Create' }}
                            </button>
                            @if($editing)
                            <button type="button" wire:click="resetForm"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                                Cancel
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                @if($branches->count() > 0)
                <div class="space-y-3">
                    @foreach($branches as $branchItem)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h3 class="font-semibold text-gray-800">{{ $branchItem->name }}</h3>
                                    @if($branchItem->is_active)
                                    <span
                                        class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Active</span>
                                    @else
                                    <span
                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Inactive</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $branchItem->address }}</p>
                                @if($branchItem->contact_number)
                                <p class="text-sm text-gray-500">{{ $branchItem->contact_number }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button wire:click="toggleStatus({{ $branchItem->id }})"
                                    class="px-2 py-1 text-xs rounded-lg {{ $branchItem->is_active ? 'bg-yellow-50 text-yellow-600' : 'bg-green-50 text-green-600' }}">
                                    {{ $branchItem->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button wire:click="editBranch({{ $branchItem->id }})"
                                    class="px-2 py-1 text-xs rounded-lg bg-blue-50 text-blue-600">Edit</button>
                                <button wire:click="deleteBranch({{ $branchItem->id }})"
                                    onclick="confirm('Delete this branch?') || event.stopImmediatePropagation()"
                                    class="px-2 py-1 text-xs rounded-lg bg-red-50 text-red-600">Delete</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">
                    <p>No branches yet. Click "New Branch" to add one.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>