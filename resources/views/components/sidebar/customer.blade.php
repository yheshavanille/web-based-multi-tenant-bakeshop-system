@php
$isActive = fn($routeName) => request()->routeIs($routeName);
@endphp

{{-- ============ SHOPPING ============ --}}
<x-sidebar.group label="Shopping" />

<x-sidebar.item :href="route('livewire.customer.dashboard')" :active="$isActive('livewire.customer.dashboard')"
    label="Dashboard">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item :href="route('livewire.customer.browse-shops')"
    :active="$isActive('livewire.customer.browse-shops') || $isActive('livewire.customer.view-products')"
    label="Browse Shops">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item :href="route('livewire.customer.orders')" :active="$isActive('livewire.customer.orders')"
    label="My Orders">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>