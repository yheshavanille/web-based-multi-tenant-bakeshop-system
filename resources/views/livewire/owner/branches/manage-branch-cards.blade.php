<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">🏪 Manage Branches</h1>
                <p class="text-sm text-gray-500">View and manage your bakeshop branches</p>
            </div>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                + Add New Branch
            </a>
        </div>

        @if($branches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $branch)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $branch->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $branch->address }}</p>
                        </div>
                        <span
                            class="px-2.5 py-1 text-xs font-medium rounded-full
                                    {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-gray-600">📦 {{ $branch->products_count ?? 0 }} products</span>
                        <span class="text-gray-600">👥 {{ $branch->employees_count ?? 0 }} employees</span>
                    </div>

                    <div class="flex flex-col gap-2 pt-2">
                        <a href="{{ route('livewire.owner.products.view-product', ['branch' => $branch->id]) }}"
                            class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium text-center">
                            View Products →
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('livewire.owner.employees.manage', ['branch' => $branch->id]) }}"
                                class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-xs text-center">
                                👥 Manage Employees
                            </a>
                            <a href="{{ route('livewire.owner.products.view-product', ['branch' => $branch->id]) }}"
                                class="flex-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-xs text-center">
                                📦 Manage Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-gray-500">
            <div class="text-4xl mb-2">🏪</div>
            <p class="text-lg">No branches yet.</p>
            <p class="text-sm text-gray-400">Create your first branch to start managing your bakeshop.</p>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="inline-block mt-4 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                Create Branch →
            </a>
        </div>
        @endif
    </div>
</div>