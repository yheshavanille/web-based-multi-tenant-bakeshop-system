<div>
    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Shop</h1>
                <p class="text-xs text-gray-500">Update your shop details and information</p>
            </div>
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-xs font-medium shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>

        <!-- Success Message -->
        @if(session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('message') }}
        </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form wire:submit.prevent="save" class="p-5">

                <!-- Shop Image Upload -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Shop Image
                    </label>

                    <!-- Image Preview - SQUARE -->
                    <div class="relative mb-3">
                        @if($image)
                        <div class="w-48 h-48 mx-auto">
                            <img src="{{ $image->temporaryUrl() }}" alt="Shop Image Preview"
                                class="w-full h-full object-cover rounded-lg border border-gray-200">
                        </div>
                        <button type="button" wire:click="removeImage"
                            class="absolute top-2 right-2 p-1 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @elseif($shop_image)
                        <div class="w-48 h-48 mx-auto">
                            <img src="{{ $shop_image }}" alt="Shop Image"
                                class="w-full h-full object-cover rounded-lg border border-gray-200">
                        </div>
                        <button type="button" wire:click="removeShopImageUrl"
                            class="absolute top-2 right-2 p-1 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @else
                        <div
                            class="w-48 h-48 mx-auto bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="mt-1 text-xs text-gray-500">No image uploaded</p>

                        </div>
                        @endif
                    </div>

                    <!-- File Input -->
                    <div class="flex items-center justify-center gap-3">
                        <label class="cursor-pointer">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-xs font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Choose Image
                            </span>
                            <input type="file" wire:model="image" accept="image/*" class="hidden">
                        </label>
                        <span class="text-xs text-gray-400">Max 2MB • JPG, PNG, GIF</span>
                    </div>
                    @error('image')
                    <p class="mt-1 text-xs text-red-600 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-200 my-4">

                <!-- Shop Name -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Shop Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="shop_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('shop_name') border-red-500 @enderror"
                        placeholder="Enter your shop name">
                    @error('shop_name')
                    <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('address') border-red-500 @enderror"
                        placeholder="Enter your shop address">
                    @error('address')
                    <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea wire:model="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm @error('description') border-red-500 @enderror"
                        placeholder="Describe your bakeshop..."></textarea>
                    @error('description')
                    <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                    <button type="button" wire:click="deleteShop"
                        onclick="confirm('⚠️ Are you sure you want to delete your shop?\n\nAll products, orders, and data will be hidden.\nThis can be reversed by contacting support.') || event.stopImmediatePropagation()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Delete Shop
                    </button>
                    <div class="flex gap-2">
                        <a href="{{ route('livewire.owner.dashboard') }}"
                            class="px-4 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-xs font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition text-xs font-medium shadow-sm">
                            Update Shop
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <!-- Danger Zone Notice - Compact -->
        <div class="mt-3 p-2.5 bg-red-50 rounded-lg border border-red-200">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <div>
                    <p class="text-xs font-medium text-red-800">⚠️ Danger Zone</p>
                    <p class="text-xs text-red-600">Deleting your shop will hide all your products, orders, and data.
                        Contact support to restore.</p>
                </div>
            </div>
        </div>

    </div>
</div>