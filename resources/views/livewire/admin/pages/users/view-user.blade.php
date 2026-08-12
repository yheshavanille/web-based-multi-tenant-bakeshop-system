<div>
    <!-- Table Section -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">

                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">

                        <!-- SUCCESS MESSAGES -->
                        @if (session()->has('message'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                            {{ session('message') }}
                        </div>
                        @endif

                        @if(session()->has('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                            {{ session('success') }}
                        </div>
                        @endif

                        <!-- HEADER -->
                        <div
                            class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">

                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Users
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-neutral-400">
                                    Manage system users and their roles
                                </p>
                            </div>

                            <!-- FIXED BUTTONS -->
                            <div class="flex gap-2">
                                <a href="{{ route('livewire.admin.admin-dashboard') }}"
                                    class="py-2 px-3 text-sm font-medium rounded-lg bg-gray-600 text-white hover:bg-gray-700">
                                    Back to Dashboard
                                </a>

                                <a href="{{ route('livewire.admin.pages.users.create-user') }}"
                                    class="py-2 px-3 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                    Add User
                                </a>
                            </div>

                        </div>

                        <!-- TABLE -->
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">

                            <!-- HEAD -->
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">Name</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">Email</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">Role</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">Created</th>
                                    <th class="px-6 py-3 text-end text-xs font-semibold uppercase">Actions</th>
                                </tr>
                            </thead>

                            <!-- BODY -->
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @foreach($users as $user)
                                <tr>

                                    <td class="px-6 py-3">
                                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                            {{ $user->name }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-500 dark:text-neutral-400">
                                            {{ $user->email }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-500 dark:text-neutral-400">
                                            {{ $user->roles->pluck('name')->join(', ') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-500 dark:text-neutral-400">
                                            {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-3 text-end">
                                        <a href="{{ route('livewire.admin.pages.users.edit-user', $user->id) }}"
                                            class="text-sm font-medium text-blue-600 hover:underline">
                                            Edit
                                        </a>

                                        <button wire:click.prevent="delete({{ $user->id }})"
                                            class="text-sm font-medium text-red-600 hover:underline ml-3">
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>