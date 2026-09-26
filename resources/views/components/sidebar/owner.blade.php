@php
    // Helper to determine if a route (or pattern) is active
    $isActive = function ($routeName) {
        return request()->routeIs($routeName);
    };
@endphp

{{-- ============ OVERVIEW ============ --}}
<x-sidebar.group label="Overview" />

<x-sidebar.item
    :href="route('livewire.owner.dashboard')"
    :active="$isActive('livewire.owner.dashboard')"
    label="Dashboard">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ CATALOG ============ --}}
<x-sidebar.group label="Catalog" />

<x-sidebar.item
    :href="route('livewire.owner.products.view-product')"
    :active="$isActive('livewire.owner.products.*')"
    label="Products">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item
    :href="route('livewire.owner.category.view-category')"
    :active="$isActive('livewire.owner.category.*')"
    label="Categories">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ BRANCHES ============ --}}
<x-sidebar.group label="Branches" />

<x-sidebar.item
    :href="route('livewire.owner.branches.manage-cards')"
    :active="$isActive('livewire.owner.branches.manage-cards')"
    label="View Branches">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item
    :href="route('livewire.owner.branches.manage-branches')"
    :active="$isActive('livewire.owner.branches.manage-branches')"
    label="Branch Lists">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ ORDERS ============ --}}
<x-sidebar.group label="Orders" />

<x-sidebar.item
    :href="route('livewire.owner.orders')"
    :active="$isActive('livewire.owner.orders')"
    label="All Orders">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

@php
    $firstBranch = auth()->user()->shop?->branches->first();
@endphp
@if($firstBranch)
<x-sidebar.item
    :href="route('livewire.owner.branches.branch-orders', ['branchId' => $firstBranch->id])"
    :active="$isActive('livewire.owner.branches.branch-orders')"
    label="Order History">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>
@endif

{{-- ============ SALES & REPORTS ============ --}}
<x-sidebar.group label="Sales & Reports" />

<x-sidebar.item
    :href="route('livewire.owner.sales-report')"
    :active="$isActive('livewire.owner.sales-report')"
    label="Sales Report">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item
    :href="route('livewire.owner.reviews-history')"
    :active="$isActive('livewire.owner.reviews-history')"
    label="Reviews History">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ TEAM ============ --}}
<x-sidebar.group label="Team" />

<x-sidebar.item
    :href="route('livewire.owner.employees.manage')"
    :active="$isActive('livewire.owner.employees.*')"
    label="Employees">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item
    :href="route('livewire.owner.employee-activities')"
    :active="$isActive('livewire.owner.employee-activities')"
    label="Employee Activities">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ HISTORY & AUDIT ============ --}}
<x-sidebar.group label="History & Audit" />

<x-sidebar.item
    :href="route('livewire.owner.product-history')"
    :active="$isActive('livewire.owner.product-history')"
    label="Product Edit History">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item
    :href="route('livewire.owner.stock-history')"
    :active="$isActive('livewire.owner.stock-history')"
    label="Stock Update History">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ ACCOUNT ============ --}}
<x-sidebar.group label="Account" />

<x-sidebar.item
    :href="route('livewire.owner.shop.edit-shop')"
    :active="$isActive('livewire.owner.shop.edit-shop')"
    label="Shop Settings">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>
