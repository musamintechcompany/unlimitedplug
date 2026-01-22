<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        @include('components.seo', [
            'title' => $title ?? null,
            'description' => $description ?? null,
            'keywords' => $keywords ?? null,
            'image' => $image ?? null,
            'url' => $url ?? null,
            'type' => $type ?? 'website'
        ])

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=space-grotesk:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-white text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }" @sidebar-toggle.window="sidebarOpen = $event.detail.open">
        <x-layouts.welcome-nav />
        
        <main class="pt-16">
            {{ $slot }}
        </main>
        
        <x-layouts.footer />
        
        @include('modals.login')
        @include('modals.newsletter')
        @include('components.cart-sidebar')
        @auth
            @include('user.notifications.index')
        @endauth
        @include('modals.cookies')
        @include('modals.guest-favorite-warning')
        
        <script>
            function setCurrency(currency) {
                fetch('/currency/set', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ currency: currency })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('selected-currency').textContent = currency;
                        const mobileCurrency = document.getElementById('selected-currency-mobile');
                        if (mobileCurrency) mobileCurrency.textContent = currency;
                        location.reload();
                    }
                })
                .catch(error => console.error('Error setting currency:', error));
            }
        </script>
    </body>
</html>