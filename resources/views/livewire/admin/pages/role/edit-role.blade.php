<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="mt-12 max-w-full mx-auto">

            <!-- Card -->
            <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 dark:border-neutral-700">

                <h2 class="mb-8 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Edit Role & Permission
                </h2>

                <form wire:submit.prevent="update">

                    <!-- Role Name -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">
                                Role Name
                            </label>

                            <input type="text" wire:model="name"
                                class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300" />

                            @error('name')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mt-6">
                        <h2 class="mb-3 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            Assign Permissions
                        </h2>

                        @error('selectedPermissions')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror

                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-2 mt-3">

                            @foreach ($permissions as $permission)

                            <label class="flex items-center p-3 border rounded-lg dark:border-neutral-700">

                                <input type="checkbox" value="{{ $permission->name }}" wire:model="selectedPermissions"
                                    class="mr-2">

                                <span class="text-sm dark:text-neutral-200">
                                    {{ str_replace('_', ' ', $permission->name) }}
                                </span>

                            </label>

                            @endforeach

                        </div>
                    </div>

                    <!-- Button -->
                    <div class="mt-6">
                        <button type="submit" class="py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update Role
                        </button>
                    </div>

                </form>
            </div>
            <!-- End Card -->

        </div>
    </div>
</div>