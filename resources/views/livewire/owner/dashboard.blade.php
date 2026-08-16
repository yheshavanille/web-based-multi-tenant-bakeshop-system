<div>
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center text-2xl">
                🏪
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-500">Manage your bakeshop and track performance.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards - VERTICAL layout with big icons -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <!-- Branch Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition flex flex-col items-center justify-center text-center"
            style="min-height: 120px;">
            <div class="text-5xl mb-2">📍</div>
            <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Branches</p>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $branches->count() }}</p>
        </div>

        <!-- Product Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition flex flex-col items-center justify-center text-center"
            style="min-height: 120px;">
            <div class="text-5xl mb-2">📦</div>
            <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Products</p>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
        </div>

        <!-- Employee Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition flex flex-col items-center justify-center text-center"
            style="min-height: 120px;">
            <div class="text-5xl mb-2">👥</div>
            <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Employees</p>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $employeesCount }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('livewire.owner.products.create-product') }}"
            class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center hover:bg-amber-100 transition">
            <div class="text-2xl mb-1">➕</div>
            <p class="text-sm font-medium text-amber-700">Add Product</p>
        </a>
        <a href="{{ route('livewire.owner.branches.manage-branches') }}"
            class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center hover:bg-blue-100 transition">
            <div class="text-2xl mb-1">📍</div>
            <p class="text-sm font-medium text-blue-700">Manage Branches</p>
        </a>
        <a href="{{ route('livewire.owner.employees.manage') }}"
            class="bg-green-50 border border-green-200 rounded-xl p-4 text-center hover:bg-green-100 transition">
            <div class="text-2xl mb-1">👥</div>
            <p class="text-sm font-medium text-green-700">Manage Employees</p>
        </a>
        <a href="{{ route('livewire.owner.shop.edit-shop') }}"
            class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center hover:bg-purple-100 transition">
            <div class="text-2xl mb-1">⚙️</div>
            <p class="text-sm font-medium text-purple-700">Shop Settings</p>
        </a>
    </div>

    <!-- Recent Branches -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">📍 Your Branches</h2>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}"
                class="text-sm text-amber-600 hover:text-amber-700">
                View All →
            </a>
        </div>
        @if($branches->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($branches as $branch)
            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $branch->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $branch->address }}</p>
                    </div>
                    <span
                        class="px-2 py-1 text-xs rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    📦 {{ $branch->products_count ?? 0 }} products
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 text-gray-500">
            <p>No branches yet.</p>
            <a href="{{ route('livewire.owner.branches.manage-branches') }}" class="text-amber-600 hover:underline">
                Create your first branch →
            </a>
        </div>
        @endif
    </div>
</div>