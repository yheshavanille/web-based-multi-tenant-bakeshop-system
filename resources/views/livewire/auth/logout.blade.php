<div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Logout</h2>
            <p class="mb-4 text-gray-600">Are you sure you want to logout?</p>
            <div class="flex justify-end gap-4">
                <button wire:click="logout"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50">
                    Logout
                </button>
                <a href="{{ route('livewire.customer.dashboard') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                    Cancel
                </a>
            </div>
        </div>
    </div>