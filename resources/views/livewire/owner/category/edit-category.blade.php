<div>
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">

            <!-- HEADER -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            Edit Category
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Update your category name.
                        </p>
                    </div>

                    <a href="{{ route('livewire.owner.category.view-category') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Back
                    </a>
                </div>
            </div>

            <!-- SUCCESS MESSAGE -->
            @if (session()->has('message'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-200 text-green-700">
                {{ session('message') }}
            </div>
            @endif

            <!-- FORM -->
            <form wire:submit.prevent="save">

                <div class="space-y-5">

                    <!-- CATEGORY NAME -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name
                        </label>

                        <input type="text" wire:model="name" class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm
                               focus:border-blue-500 focus:ring-blue-500">

                        @error('name')
                        <span class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <!-- BUTTON -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                            Update Category
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>