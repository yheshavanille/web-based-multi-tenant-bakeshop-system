@php
$user = auth()->user();
$employee = $user->employee;
$isActive = fn($routeName) => request()->routeIs($routeName);
@endphp

{{-- ============ OVERVIEW ============ --}}
<x-sidebar.group label="Overview" />

<x-sidebar.item :href="route('livewire.employee.dashboard')" :active="$isActive('livewire.employee.dashboard')"
    label="Dashboard">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ WORKSPACE ============ --}}
@if($employee && in_array($employee->role, ['order_manager', 'inventory_manager']))
<x-sidebar.group label="Workspace" />
@endif

@if($employee?->role === 'order_manager')
<x-sidebar.item :href="route('livewire.employee.products')" :active="$isActive('livewire.employee.products')"
    label="Manage Products">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item :href="route('livewire.employee.orders')" :active="$isActive('livewire.employee.orders')"
    label="Orders">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>
@endif

@if($employee?->role === 'inventory_manager')
<x-sidebar.item :href="route('livewire.employee.inventory')" :active="$isActive('livewire.employee.inventory')"
    label="Manage Stock">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item :href="route('livewire.employee.stock-history')" :active="$isActive('livewire.employee.stock-history')"
    label="Stock History">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>
@endif