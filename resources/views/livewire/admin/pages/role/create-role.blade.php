<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="mt-12 max-w-full mx-auto">
            <!-- Card -->
            <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 dark:border-neutral-700">
                <h2 class="mb-8 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Create Role & Permission
                </h2>

                <form wire:submit.prevent='save'>
                    <div class="grid gap-4 lg:gap-6">
                        <!-- Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <label for="hs-name"
                                    class="block mb-2 text-sm text-gray-700 font-medium dark:text-white">Role
                                    Name</label>
                                <input wire:model.defer='name' type="text" name="hs-name" id="hs-name"
                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">


                            </div>
                        </div>
                        <div class="mt-5">
                            <h2 class="mb-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                Assign Permissions
                            </h2>

                            @error('selectedPermissions')
                            <div>
                                <span class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</span>
                            </div>
                            @enderror

                        </div>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-2">

                            @forelse ($this->permissions as $pKey => $permission)

                            <label wire:key='{{ $pKey }}' for="hs-checkbox-in-form-{{ $pKey }}"
                                class="flex items-center p-3 w-full bg-layer border dark:border-neutral-700 border-layer-line rounded-lg text-sm focus:border-primary-focus focus:ring-primary-focus">
                                <input type="checkbox"
                                    class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none"
                                    id="hs-checkbox-in-form-{{ $pKey }}" wire:model='selectedPermissions'
                                    value="{{ $permission->name }}">
                                <span class="text-sm ms-3 text-muted-foreground-1 dark:text-amber-50">
                                    {{ str_replace('_',' ', $permission->name) }}
                                </span>

                            </label>

                            @empty
                            <p>No Permissions Found</p>
                            @endforelse




                        </div>

                    </div>
                    <!-- End Grid -->

                    <div class="mt-6 grid">
                        <button type="submit"
                            class="w-50 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Save</button>
                    </div>

                </form>
            </div>
            <!-- End Card -->
        </div>
    </div>
    <!-- End Contact Us -->
</div>