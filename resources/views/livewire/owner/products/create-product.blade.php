<div>
    <div>
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="mt-12 max-w-full mx-auto pb-24">

                <div class="flex flex-col border border-gray-200 rounded-xl p-4 sm:p-6 lg:p-8 overflow-visible">

                    <!-- FLASH MESSAGE -->
                    @if (session()->has('message'))
                    <div
                        class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
                        <span>{{ session('message') }}</span>
                        <button onclick="this.parentElement.remove()"
                            class="text-green-700 hover:text-green-900">✕</button>
                    </div>
                    @endif

                    <div class="flex flex-col mb-8">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">
                                Create Product
                            </h2>

                            <a href="{{ route('livewire.owner.dashboard') }}"
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                Back to Dashboard
                            </a>
                        </div>

                        <p class="text-sm text-gray-600 mt-1">
                            Create Products for your shop. Provide details like name, price, branch, category, and an
                            image to attract customers.
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
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Product Name</label>
                                    <input type="text" wire:model="name"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                    @error('name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Price (₱)</label>
                                    <input type="number" step="0.01" wire:model="price"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                    @error('price')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                                    <select wire:model="category_id"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg bg-white focus:ring-amber-500 focus:border-amber-500">
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

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Stock per Branch <span
                                            class="text-xs text-gray-400">(optional)</span></label>
                                    <input type="number" wire:model="stock_per_branch" min="0"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                    <p class="text-xs text-gray-500 mt-1">Default stock quantity for selected branches
                                        (leave 0 if you want to set stock later)</p>
                                </div>
                            </div>

                            <!-- BRANCH CHECKBOXES -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Available Branches</label>
                                <p class="text-sm text-gray-500 mb-3">Select which branches this product is available
                                    at.</p>

                                @if($branches->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($branches as $branch)
                                    <label
                                        class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg hover:bg-amber-50 cursor-pointer transition">
                                        <input type="checkbox" wire:model="selectedBranches" value="{{ $branch->id }}"
                                            class="mt-0.5 w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $branch->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $branch->address }}</p>
                                            <p class="text-xs text-gray-400">Status: {{ $branch->is_active ? 'Active' :
                                                'Inactive' }}</p>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('selectedBranches')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                                @else
                                <div
                                    class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                                    <p>⚠️ No branches found. Please <a
                                            href="{{ route('livewire.owner.branches.manage-branches') }}"
                                            class="text-amber-600 hover:underline">create a branch</a> first.</p>
                                </div>
                                @endif
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="description"
                                    class="py-2.5 px-4 w-full border border-gray-200 rounded-lg min-h-[120px] focus:ring-amber-500 focus:border-amber-500"></textarea>
                                @error('description')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- ✅ DISCOUNT SECTION -->
                            <div class="border-t border-gray-200 pt-4 mt-2">
                                <h3 class="text-md font-semibold text-gray-800 mb-3">🏷️ Discount Settings</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Discount
                                            Type</label>
                                        <select wire:model="discount_type"
                                            class="py-2.5 px-4 w-full border border-gray-200 rounded-lg bg-white focus:ring-amber-500 focus:border-amber-500">
                                            <option value="none">No Discount</option>
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="fixed">Fixed Amount (₱)</option>
                                        </select>
                                        @error('discount_type')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">
                                            Discount Value
                                            <span class="text-xs text-gray-400">
                                                ({{ $discount_type === 'percentage' ? 'e.g., 20 for 20%' : 'e.g., 100
                                                for ₱100 off' }})
                                            </span>
                                        </label>
                                        <input type="number" step="0.01" wire:model="discount_value" min="0"
                                            class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                            {{ $discount_type==='none' ? 'disabled' : '' }}>
                                        @error('discount_value')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Discount Start
                                            Date</label>
                                        <input type="datetime-local" wire:model="discount_start"
                                            class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                        @error('discount_start')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Discount End
                                            Date</label>
                                        <input type="datetime-local" wire:model="discount_end"
                                            class="py-2.5 px-4 w-full border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                        @error('discount_end')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Image URL</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" wire:model.live="image_url" @disabled($image)
                                        placeholder="https://example.com/image.jpg"
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg bg-white relative z-20 disabled:bg-gray-100 disabled:cursor-not-allowed focus:ring-amber-500 focus:border-amber-500">
                                    @if($image_url)
                                    <button type="button" wire:click="$set('image_url', '')"
                                        class="h-10 w-10 flex items-center justify-center border rounded-lg text-gray-500 hover:text-red-500 hover:border-red-300">
                                        ✕
                                    </button>
                                    @endif
                                </div>
                                @error('image_url')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Upload Image</label>
                                <div class="flex items-center gap-2">
                                    <input type="file" wire:model="image" @disabled($image_url)
                                        class="py-2.5 px-4 w-full border border-gray-200 rounded-lg disabled:bg-gray-100 disabled:cursor-not-allowed focus:ring-amber-500 focus:border-amber-500">
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

                        <div class="mt-6 grid">
                            <button type="submit"
                                class="w-50 py-3 px-4 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>