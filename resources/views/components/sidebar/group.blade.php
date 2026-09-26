@props(['label'])

<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-cloak
    class="px-5 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 whitespace-nowrap">
    {{ $label }}
</div>