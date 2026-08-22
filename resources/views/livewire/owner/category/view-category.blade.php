<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">🏷️ Categories</h1>
                <p class="text-sm text-gray-500">Manage your shop categories</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('livewire.owner.dashboard') }}"
                    class="px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    ← Dashboard
                </a>
                <a href="{{ route('livewire.owner.category.create-category') }}"
                    class="px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    + Add Category
                </a>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>✅ {{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
        </div>
        @endif

        <!-- Categories Grid -->
        @if($categories->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $category->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $category->shop_id ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $category->shop_id ? 'Custom' : 'Default' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $category->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ $category->products->count() }} products</p>
                    </div>
                    @if($category->shop_id)
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
            <span class="text-6xl block mb-4">📭</span>
            <p class="text-gray-500 text-lg">No categories yet</p>
            <p class="text-sm text-gray-400">Create your first category to organize your products.</p>
            <a href="{{ route('livewire.owner.category.create-category') }}"
                class="inline-block mt-4 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                + Add Category
            </a>
        </div>
        @endif

    </div>
</div>