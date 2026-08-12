<div>
    <!-- Table Section -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">

                    <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden">

                        <!-- SUCCESS MESSAGE -->
                        @if (session()->has('message'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                            {{ session('message') }}
                        </div>
                        @endif

                        <!-- HEADER -->
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200">

                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">
                                    Categories
                                </h2>
                                <p class="text-sm text-gray-600">
                                    Manage your shop categories
                                </p>
                            </div>

                            <div class="flex gap-2">

                                <!-- BACK -->
                                <a href="{{ route('livewire.owner.dashboard') }}"
                                    class="py-2 px-3 text-sm font-medium rounded-lg bg-gray-600 text-white hover:bg-gray-700">
                                    Back
                                </a>

                                <!-- ADD CATEGORY -->
                                <a href="{{ route('livewire.owner.category.create-category') }}"
                                    class="py-2 px-3 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                    Add Category
                                </a>

                            </div>

                        </div>

                        <!-- TABLE -->
                        <table class="min-w-full divide-y divide-gray-200">

                            <!-- HEAD -->
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">
                                        Category Name
                                    </th>

                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">
                                        Type
                                    </th>

                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase">
                                        Created
                                    </th>

                                    <th class="px-6 py-3 text-end text-xs font-semibold uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <!-- BODY -->
                            <tbody class="divide-y divide-gray-200">

                                @foreach($categories as $category)
                                <tr>

                                    <!-- NAME -->
                                    <td class="px-6 py-3">
                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ $category->name }}
                                        </span>
                                    </td>

                                    <!-- TYPE -->
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-500">
                                            {{ $category->shop_id ? 'Custom' : 'Default' }}
                                        </span>
                                    </td>

                                    <!-- CREATED -->
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-500">
                                            {{ $category->created_at->diffForHumans() }}
                                        </span>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="px-6 py-3 text-end">

                                        @if($category->shop_id)
                                        <a href="{{ route('livewire.owner.category.edit-category', $category->id) }}"
                                            class="text-sm font-medium text-blue-600 hover:underline">
                                            Edit
                                        </a>

                                        <button wire:click="delete({{ $category->id }})"
                                            class="text-sm font-medium text-red-600 hover:underline ml-3">
                                            Delete
                                        </button>
                                        @else
                                        <span class="text-xs text-gray-400">
                                            System Category
                                        </span>
                                        @endif

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