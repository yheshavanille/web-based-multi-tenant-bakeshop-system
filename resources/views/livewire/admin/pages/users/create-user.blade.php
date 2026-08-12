<div>
    <div>
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="mt-12 max-w-full mx-auto">

                <!-- Card -->
                <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 dark:border-neutral-700">

                    <!--  CENTERED TITLE -->
                    <div class="flex justify-center mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            Create User
                        </h2>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="grid gap-4 lg:gap-6">

                            <!-- Name & Email -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label
                                        class="block mb-2 text-sm text-gray-700 font-medium dark:text-white">Name</label>
                                    <input wire:model.defer="name" type="text"
                                        class="py-2.5 sm:py-3 px-4 w-full border border-gray-200 rounded-lg">
                                    @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label
                                        class="block mb-2 text-sm text-gray-700 font-medium dark:text-white">Email</label>
                                    <input wire:model.defer="email" type="email"
                                        class="py-2.5 sm:py-3 px-4 w-full border border-gray-200 rounded-lg">
                                    @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label
                                        class="block mb-2 text-sm text-gray-700 font-medium dark:text-white">Password</label>
                                    <input wire:model.defer="password" type="password"
                                        class="py-2.5 sm:py-3 px-4 w-full border border-gray-200 rounded-lg">
                                    @error('password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm text-gray-700 font-medium dark:text-white">
                                        Confirm Password
                                    </label>
                                    <input wire:model.defer="password_confirmation" type="password"
                                        class="py-2.5 sm:py-3 px-4 w-full border border-gray-200 rounded-lg">
                                    @error('password_confirmation')<div class="text-red-500 text-sm mt-1">{{ $message }}
                                    </div>@enderror
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="mt-5">
                                <h2 class="mb-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                    Assign Role
                                </h2>
                                @error('selectedRole') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <select wire:model.defer="selectedRole"
                                    class="w-full py-2.5 px-4 border border-gray-200 rounded-lg">
                                    <option value="">Select role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <!--  BUTTONS (BOTTOM RIGHT) -->
                        <div class="mt-6 flex justify-end gap-3">

                            <!-- BACK BUTTON -->
                            <a href="{{ route('livewire.admin.admin-dashboard') }}"
                                class="inline-flex items-center px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                ← Back to Dashboard
                            </a>

                            <!-- SAVE BUTTON -->
                            <button type="submit"
                                class="py-2.5 px-5 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                Save
                            </button>

                        </div>

                    </form>
                </div>
                <!-- End Card -->

            </div>
        </div>
    </div>
</div>