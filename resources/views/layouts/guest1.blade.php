<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=space-grotesk:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-sans antialiased" x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }" @sidebar-toggle.window="sidebarOpen = $event.detail.open">
        <x-layouts.welcome-nav />
        
        <main class="pt-16">
            {{ $slot }}
        </main>
        
        @include('modals.login')
        @include('components.cart-sidebar')
    </body>
</html>