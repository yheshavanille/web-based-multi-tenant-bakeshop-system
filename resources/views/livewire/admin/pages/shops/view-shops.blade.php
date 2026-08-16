<div>
    <div>
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

            <div class="mt-12 max-w-full mx-auto">

                <!-- HEADER ROW -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-4">
                        <button wire:click="toggleDeleted"
                            class="inline-flex items-center px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-gray-600 text-white hover:bg-gray-700' }}">
                            {{ $showDeleted ? '📋 Show Active' : '🗑️ Show Deleted' }}
                        </button>
                        @if($deletedCount > 0)
                        <span class="text-sm text-gray-500">{{ $deletedCount }} deleted shop(s)</span>
                        @endif
                    </div>
                    <a href="{{ route('livewire.admin.admin-dashboard') }}"
                        class="inline-flex items-center px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        ← Back to Dashboard
                    </a>
                </div>

                <!-- HEADER -->
                <div class="flex flex-col items-center justify-center mb-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $showDeleted ? '🗑️ Deleted Bakeshops' : '📋 View Bakeshops' }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $showDeleted ? 'View and restore deleted bakeshops.' : 'Manage bakeshop listings and
                        details.' }}
                    </p>
                </div>

                <!-- Flash Message -->
                @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
                @endif

                <!-- Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @forelse($shops as $shop)
                    <!-- Card -->
                    <div
                        class="group flex flex-col h-full bg-white border border-gray-200 ring-1 ring-gray-100 shadow-sm rounded-xl overflow-hidden {{ $shop->trashed() ? 'opacity-75 border-red-200' : '' }}">

                        <!-- IMAGE WRAPPER -->
                        <div class="p-3">
                            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                                @if($shop->shop_image)
                                <img src="{{ $shop->shop_image }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- CONTENT -->
                        <div class="p-4 md:p-6">
                            <div class="flex items-start justify-between">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    {{ $shop->shop_name }}
                                </h3>
                                @if($shop->trashed())
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Deleted</span>
                                @endif
                            </div>

                            <p class="mt-2 text-sm text-gray-500">
                                Owner: {{ $shop->user->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $shop->user->email ?? '' }}
                            </p>
                            <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                                {{ $shop->description }}
                            </p>
                        </div>

                        <!-- ACTIONS -->
                        <div class="mt-auto flex border-t border-gray-200 divide-x divide-gray-200">

                            @if($shop->trashed())
                            <!-- Restore -->
                            <button wire:click="restore({{ $shop->id }})"
                                class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium rounded-es-xl text-green-600 hover:bg-green-50">
                                Restore
                            </button>
                            <!-- Permanent Delete -->
                            <button wire:click="forceDelete({{ $shop->id }})"
                                onclick="confirm('Permanently delete this shop? This cannot be undone.') || event.stopImmediatePropagation()"
                                class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium rounded-ee-xl text-red-600 hover:bg-red-50">
                                Delete Permanently
                            </button>
                            @else
                            <!-- View Shop -->
                            <a href="{{ route('livewire.admin.pages.shops.shop-details', ['shopId' => $shop->id]) }}"
                                class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium rounded-es-xl hover:bg-gray-50">
                                View Shop
                            </a>
                            <!-- Delete -->
                            <button wire:click="delete({{ $shop->id }})"
                                onclick="confirm('Are you sure you want to delete this shop?') || event.stopImmediatePropagation()"
                                class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-medium rounded-ee-xl text-red-600 hover:bg-red-50">
                                Delete
                            </button>
                            @endif

                        </div>

                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        <p>{{ $showDeleted ? 'No deleted shops found.' : 'No bakeshops available.' }}</p>
                    </div>
                    @endforelse

                </div>
                <!-- End Grid -->
            </div>
        </div>
    </div>
</div>