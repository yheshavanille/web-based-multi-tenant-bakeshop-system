<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <!-- Navbar -->
    <x-navbar.customer />

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </div>
    </div>

    <!-- Toast Notification -->
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
        @click.away="show = false"
        class="fixed bottom-6 right-6 z-50 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg max-w-sm flex items-center gap-3">
        <span class="text-2xl">✅</span>
        <span class="font-medium" x-text="message"></span>
    </div>

    @livewireScripts
</body>

</html>