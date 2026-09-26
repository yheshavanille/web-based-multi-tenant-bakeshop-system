@props([
'href' => '#',
'active' => false,
'label' => '',
'badge' => null,
])

<div class="relative group">
    <a href="{{ $href }}" class="flex items-center gap-3 mx-2 px-3 py-2 rounded-lg text-sm font-medium transition
              {{ $active
                    ? 'bg-amber-50 text-amber-700'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" title="{{ $label }}">
        @isset($icon)
        <span class="flex-shrink-0 w-5 h-5 relative {{ $active ? 'text-amber-600' : 'text-gray-500' }}">
            {{ $icon }}
            @if($badge)
            {{-- Tiny dot when collapsed --}}
            <span x-show="!sidebarOpen" class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full" x-cloak></span>
            @endif
        </span>
        @endisset

        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
            class="truncate whitespace-nowrap flex-1">
            {{ $label }}
        </span>

        @if($badge)
        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
            class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full whitespace-nowrap">
            {{ $badge }}
        </span>
        @endif
    </a>

    {{-- Tooltip when collapsed --}}
    <div x-show="!sidebarOpen" x-cloak
        class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md whitespace-nowrap pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50">
        {{ $label }}
        @if($badge)
        <span class="ml-1.5 text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-full">{{ $badge }}</span>
        @endif
    </div>
</div>