<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50">
    <x-sidebar-layout role="customer">
        {{-- Sidebar content --}}
        <x-slot:sidebar>
            @include('components.sidebar.customer')
        </x-slot:sidebar>

        {{-- Profile dropdown content --}}
        <x-slot:dropdown>
            @php
            $user = auth()->user();
            $profilePic = $user->profile_picture;
            @endphp

            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    @if($profilePic)
                    <img src="{{ asset('storage/' . $profilePic) }}?v={{ time() }}" alt="{{ $user->name }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    @else
                    <div
                        class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                    </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-slate-100 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 dark:text-slate-400">{{ $user->phone ?? 'No phone' }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('livewire.customer.profile') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                <svg class="w-4 h-4 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                My Profile
            </a>

            {{-- ✅ My Shop Dashboard — only if user owns an active shop --}}
            @if(auth()->user()->hasRole('owner') && auth()->user()->shop && !auth()->user()->shop->trashed())
            <a href="{{ route('livewire.owner.dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                <svg class="w-4 h-4 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                My Shop Dashboard
            </a>
            @endif

            <div class="border-t border-gray-100 dark:border-slate-700 my-1"></div>

            <form method="POST" action="{{ route('logout.post') }}" class="block">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </x-slot:dropdown>

        {{ $slot }}
    </x-sidebar-layout>

    <div x-data="{
        show: false,
        message: '',
        init() {
            Livewire.on('show-toast', (data) => {
                this.message = data.message;
                this.show = true;
                setTimeout(() => { this.show = false; }, 3000);
            });
        }
    }" x-show="show" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
        @click.away="show = false" x-cloak
        class="fixed bottom-6 right-6 z-50 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg max-w-sm flex items-center gap-3">
        <span class="text-2xl">✅</span>
        <span class="font-medium" x-text="message"></span>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>