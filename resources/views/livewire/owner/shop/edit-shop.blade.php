<div>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="mt-12 max-w-full mx-auto">


            <!-- ONE CARD -->
            <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 space-y-6">
                <!-- HEADER -->
                <div class="flex flex-col items-center justify-center mb-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Edit Shop
                    </h2>

                    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-1">
                        Update your shop details and information.
                    </p>
                </div>

                <!-- IMAGE PREVIEW -->
                @if($image)
                <img src="{{ $image->temporaryUrl() }}" class="w-full h-64 object-cover rounded-lg">
                @elseif($shop_image)
                <img src="{{ $shop_image }}" class="w-full h-64 object-cover rounded-lg">
                @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                    No Image Preview
                </div>
                @endif



                <!-- SUCCESS MESSAGE -->
                @if(session()->has('message'))
                <div class="p-4 rounded-lg bg-green-100 text-green-800 border border-green-200">
                    {{ session('message') }}
                </div>
                @endif

                <!-- FORM -->
                <form wire:submit.prevent="save">
                    <div class="grid gap-4 lg:gap-6">

                        <!-- SHOP NAME -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-700 font-medium">
                                Shop Name
                            </label>
                            <input type="text" wire:model="shop_name"
                                class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">
                            @error('shop_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- IMAGE URL -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-700 font-medium">
                                Shop Image URL
                            </label>

                            <div class="flex items-center gap-2">
                                <input type="text" wire:model.live="shop_image" @disabled(!empty($image))
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">

                                @if($shop_image)
                                <button type="button" wire:click="removeShopImageUrl"
                                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                    ✕
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- IMAGE FILE -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-700 font-medium">
                                Upload Shop Image
                            </label>

                            <div class="flex items-center gap-2">
                                <input type="file" wire:model="image" @disabled(!empty($shop_image))
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">

                                @if($image)
                                <button type="button" wire:click="removeImage"
                                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                    ✕
                                </button>
                                @endif
                            </div>
                        </div>


                        <!-- ADDRESS -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-700 font-medium">
                                Address
                            </label>
                            <input type="text" wire:model="address"
                                class="py-2.5 px-4 w-full border border-gray-200 rounded-lg">
                            @error('address')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- DESCRIPTION -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-700 font-medium">
                                Description
                            </label>
                            <textarea wire:model="description"
                                class="py-2.5 px-4 w-full border border-gray-200 rounded-lg"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- BUTTONS -->
                    <div class="mt-6 flex justify-end gap-3">

                        <a href="{{ route('livewire.owner.dashboard') }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center justify-center">
                            Back to Dashboard
                        </a>

                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update Shop
                        </button>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>