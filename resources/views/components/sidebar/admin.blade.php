@php
$isActive = fn($routeName) => request()->routeIs($routeName);
$pendingSellerCount = \App\Models\SellerRegistration::where('status', 'pending')->count();
$flaggedCount = \App\Models\ServiceReview::where('moderation_status', 'pending_review')->count()
+ \App\Models\ProductReview::where('moderation_status', 'pending_review')->count();
@endphp

{{-- ============ OVERVIEW ============ --}}
<x-sidebar.group label="Overview" />

<x-sidebar.item :href="route('livewire.admin.admin-dashboard')" :active="$isActive('livewire.admin.admin-dashboard')"
    label="Dashboard">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ MANAGEMENT ============ --}}
<x-sidebar.group label="Management" />

<x-sidebar.item :href="route('livewire.admin.pages.shops.view-shops')"
    :active="$isActive('livewire.admin.pages.shops.*')" label="View Shops">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item :href="route('livewire.admin.pages.users.manage-users')"
    :active="$isActive('livewire.admin.pages.users.*')" label="Manage Users">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

{{-- ============ MODERATION ============ --}}
<x-sidebar.group label="Moderation" />

<x-sidebar.item :href="route('livewire.admin.pending-sellers')" :active="$isActive('livewire.admin.pending-sellers')"
    :badge="$pendingSellerCount > 0 ? $pendingSellerCount : null" label="Pending Sellers">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>

<x-sidebar.item :href="route('livewire.admin.flagged-reviews')" :active="$isActive('livewire.admin.flagged-reviews')"
    :badge="$flaggedCount > 0 ? $flaggedCount : null" label="Flagged Reviews">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
        </svg>
    </x-slot:icon>
</x-sidebar.item>