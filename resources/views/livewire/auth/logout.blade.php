<div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-slate-900 max-sm:px-4">
        <div class="bg-white dark:bg-slate-800 p-8 max-sm:p-5 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-slate-100 mb-6">Logout</h2>
            <p class="mb-4 text-gray-600 dark:text-slate-300">Are you sure you want to logout?</p>
            <div class="flex flex-wrap justify-end gap-4">
                <button wire:click="logout"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50">
                    Logout
                </button>
                <a href="{{ route('livewire.customer.dashboard') }}"
                    class="px-4 py-2 bg-gray-300 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-400 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                    Cancel
                </a>
            </div>
        </div>
    </div>