<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="mt-12 max-w-full mx-auto pb-24">

            <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 overflow-visible">

                <div class="flex flex-col mb-8">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Branches
                        </h2>
                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="createNewBranch"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                + New Branch
                            </button>
                            <a href="{{ route('livewire.owner.dashboard') }}"
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-1">
                        Manage your shop branches. Add new locations, update contact info, and toggle active status.
                    </p>
                </div>

                @if (session()->has('message'))
                <div class="mb-8 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('message') }}
                </div>
                @endif

                <div class="grid gap-4 lg:gap-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-800">Branch List</h3>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Address</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Contact</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($branches as $branch)
                                <tr>
                                    <td class="px-4 py-3 text-gray-700">{{ $branch->id }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $branch->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $branch->address }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $branch->contact_number ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button type="button" wire:click="toggleStatus({{ $branch->id }})"
                                            class="rounded-lg border border-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-50">
                                            {{ $branch->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                        <button type="button" wire:click="editBranch({{ $branch->id }})"
                                            class="rounded-lg border border-blue-600 bg-blue-50 px-3 py-1 text-xs text-blue-700 hover:bg-blue-100">
                                            Edit
                                        </button>
                                        <button type="button" wire:click="deleteBranch({{ $branch->id }})"
                                            class="rounded-lg border border-red-600 bg-red-50 px-3 py-1 text-xs text-red-700 hover:bg-red-100">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No branches found. Click "New Branch" to create your first branch.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create/Edit Form -->
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            {{ $editing ? 'Edit Branch' : 'Create New Branch' }}
                        </h3>

                        <form wire:submit.prevent="saveBranch" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">Branch Name</label>
                                <input type="text" wire:model.defer="name"
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">Contact Number</label>
                                <input type="text" wire:model.defer="contact_number"
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                                @error('contact_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm text-gray-700 font-medium">Address</label>
                                <textarea wire:model.defer="address"
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                    rows="3"></textarea>
                                @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" wire:model.defer="is_active" id="branch-active"
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                <label for="branch-active" class="text-sm text-gray-700">Active branch</label>
                            </div>

                            <div class="md:col-span-2 flex gap-3">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                                    {{ $editing ? 'Update Branch' : 'Save Branch' }}
                                </button>
                                @if($editing)
                                <button type="button" wire:click="resetForm"
                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>