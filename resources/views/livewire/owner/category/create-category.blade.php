<div>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Create Category</h1>
                <p class="text-sm text-gray-500">Add a new category to organize your products</p>
            </div>
            <a href="{{ route('livewire.owner.category.view-category') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span class="inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" placeholder="e.g., Cakes, Breads, Pastries"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                            Create Category
                        </button>
                        <a href="{{ route('livewire.owner.category.view-category') }}"
                            class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
