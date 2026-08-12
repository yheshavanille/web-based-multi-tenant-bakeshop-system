<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="mt-12 max-w-full mx-auto">
            <!-- Card -->
            <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8">
                <h2 class="mb-8 text-xl font-semibold text-gray-800">
                    Edit User
                </h2>



                <form wire:submit.prevent="save">
                    <div class="grid gap-4 lg:gap-6">

                        <!-- NAME + EMAIL -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">
                                    Name
                                </label>
                                <input type="text" wire:model="name"
                                    class="py-2.5 px-4 w-full border-gray-200 rounded-lg">
                                @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">
                                    Email
                                </label>
                                <input type="email" wire:model="email"
                                    class="py-2.5 px-4 w-full border-gray-200 rounded-lg">
                                @error('email')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- PASSWORD -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">
                                    Password
                                </label>
                                <input type="password" wire:model="password"
                                    class="py-2.5 px-4 w-full border-gray-200 rounded-lg">
                                @error('password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">
                                    Confirm Password
                                </label>
                                <input type="password" wire:model="password_confirmation"
                                    class="py-2.5 px-4 w-full border-gray-200 rounded-lg">
                            </div>
                        </div>

                        <!-- ROLE -->
                        <div class="mt-5">
                            <h2 class="mb-1 text-lg font-semibold text-gray-800">
                                Assign Role
                            </h2>
                            @error('selectedRole')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-2">
                            <select wire:model="selectedRole" class="w-full py-2.5 px-4 border rounded-lg">
                                <option value="">Select role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ ucfirst($role->name) }}
                                </option>
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
        </div>
    </div>
</div>