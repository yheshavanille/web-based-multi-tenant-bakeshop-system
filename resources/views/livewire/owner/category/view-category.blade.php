<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
                <p class="text-sm text-gray-500">Manage your shop categories</p>
                @if($showDeleted)
                <p class="text-sm text-red-600 mt-1">Showing deleted categories</p>
                @endif
            </div>
            <div class="flex gap-3">
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-amber-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
                {{-- Show Deleted Toggle --}}
                <button wire:click="toggleDeleted"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg {{ $showDeleted ? 'bg-amber-600 text-white' : 'bg-gray-600 text-white' }} hover:bg-amber-700 transition">
                    @if($showDeleted)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Show Active
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Show Deleted
                    @endif
                </button>
                <a href="{{ route('livewire.owner.category.create-category') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Category
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute left-0 top-0 z-10 flex h-full w-10 items-center justify-center pointer-events-none"
                    style="position: absolute;">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search categories by name..."
                    class="w-full h-10 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                @if(!empty($search))
                <button wire:click="clearSearch" type="button"
                    class="absolute right-0 top-0 z-10 flex h-full w-10 items-center justify-center text-gray-400 hover:text-gray-600 transition"
                    style="position: absolute;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
            @if(!empty($search))
            <p class="mt-1 text-xs text-gray-500">
                Showing results for: <span class="font-medium text-amber-600">{{ $search }}</span>
                <span class="text-gray-400">({{ $categories->count() }} found)</span>
            </p>
            @endif
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span class="inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        @endif

        <!-- Categories Grid -->
        @if($categories->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
            @php
            $isDeleted = $category->trashed();
            @endphp
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition {{ $isDeleted ? 'opacity-70 border-red-200' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $category->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            @if($isDeleted)
                            <span
                                class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Deleted
                            </span>
                            @else
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $category->shop_id ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $category->shop_id ? 'Custom' : 'Default' }}
                            </span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $category->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ $category->products->count() }} products</p>
                    </div>
                    @if($isDeleted)
                    <div class="flex-shrink-0">
                        <button wire:click="restore({{ $category->id }})"
                            class="inline-flex items-center gap-1 text-green-600 hover:text-green-800 text-sm font-medium transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Restore
                        </button>
                    </div>
                    @elseif($category->shop_id)
                    <div class="flex gap-2 flex-shrink-0">
                        <a href="{{ route('livewire.owner.category.edit-category', $category->id) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                            Edit
                        </a>
                        <button wire:click="delete({{ $category->id }})"
                            onclick="confirm('Delete this category? Products will be uncategorized.') || event.stopImmediatePropagation()"
                            class="text-red-600 hover:text-red-800 text-sm font-medium transition">
                            Delete
                        </button>
                    </div>
                    @else
                    <span class="text-xs text-gray-400 flex-shrink-0">System</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
            </div>
            @if(!empty($search))
            <p class="text-gray-500 text-lg">No categories found matching "<span class="font-medium text-amber-600">{{
                    $search }}</span>"</p>
            <p class="text-sm text-gray-400">Try adjusting your search.</p>
            @elseif($showDeleted)
            <p class="text-gray-500 text-lg">No deleted categories</p>
            <p class="text-sm text-gray-400">Deleted categories will appear here.</p>
            @else
            <p class="text-gray-500 text-lg">No categories yet</p>
            <p class="text-sm text-gray-400">Create your first category to organize your products.</p>
            @endif
            <a href="{{ route('livewire.owner.category.create-category') }}"
                class="inline-flex items-center gap-2 mt-4 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </a>
        </div>
        @endif

    </div>
</div>