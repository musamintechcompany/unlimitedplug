<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-adsense-account" content="ca-pub-9823332712820846">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }" @sidebar-toggle.window="sidebarOpen = $event.detail.open">
        <div class="min-h-screen bg-white">
            @include('user.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow pt-6">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pt-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
        
        @include('components.newsletter-section')
        <x-layouts.footer />
        
        @include('components.cart-sidebar')
        @include('user.notifications.index')
        @include('modals.newsletter')
        @include('modals.cookies')
        
        @if(session('show_newsletter_modal'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('newsletter-modal'));
                }, 1000);
            });
        </script>
        @endif
    </body>
</html>
