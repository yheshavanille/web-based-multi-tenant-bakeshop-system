<div>
    <div>
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="mt-12 max-w-full mx-auto pb-24">

                <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 overflow-visible">

                    <div class="flex flex-col mb-8">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Edit Product</h2>

                            <a href="{{ route('livewire.owner.products.view-product') }}"
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                Back to Products
                            </a>
                        </div>

                        <p class="text-sm text-gray-600 mt-1">
                            Update product details. Make sure to save your changes before navigating away.
                        </p>
                    </div>

                    <div class="mb-8">
                        @if($image)
                        <div
                            class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="{{ $image->temporaryUrl() }}" class="max-w-full max-h-full object-contain">
                        </div>
                        @elseif($image_url)
                        <div
                            class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="{{ $image_url }}" class="max-w-full max-h-full object-contain">
                        </div>
                        @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                            No Image Preview
                        </div>
                        @endif
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="grid gap-4 lg:gap-6">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block mb-2 text-sm text-gray-700 font-medium">Product Name</label>
                                    <input type="text" wire:model="name"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">
                                    @error('name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm text-gray-700 font-medium">Price</label>
                                    <input type="number" wire:model="price"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">
                                    @error('price')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block mb-2 text-sm text-gray-700 font-medium">Branch</label>
                                    <select wire:model="branch_id"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg bg-white">
                                        <option value="">Select branch</option>
                                        @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm text-gray-700 font-medium">Category</label>
                                    <select wire:model="category_id"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">Description</label>
                                <textarea wire:model="description"
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg min-h-[120px]"></textarea>
                                @error('description')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">Image URL</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" wire:model.live="image_url" @disabled($image)
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg bg-white relative z-20 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    @if($image_url)
                                    <button type="button" wire:click="$set('image_url', '')"
                                        class="h-10 w-10 flex items-center justify-center border rounded-lg text-gray-500 hover:text-red-500 hover:border-red-300">
                                        ✕
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm text-gray-700 font-medium">Upload Image</label>
                                <div class="flex items-center gap-2">
                                    <input type="file" wire:model="image" @disabled($image_url)
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    @if($image)
                                    <button type="button" wire:click="$set('image', null)"
                                        class="h-10 w-10 flex items-center justify-center border rounded-lg text-gray-500 hover:text-red-500 hover:border-red-300">
                                        ✕
                                    </button>
                                    @endif
                                </div>
                                @error('image')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>