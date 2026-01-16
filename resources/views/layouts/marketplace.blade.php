<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-adsense-account" content="ca-pub-9823332712820846">

    <title>{{ $title ?? 'MarketPlace' }} - {{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="{{ $description ?? 'Browse and purchase digital products on our marketplace.' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900" x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }" @sidebar-toggle.window="sidebarOpen = $event.detail.open">
    <!-- Navigation -->
    <x-layouts.marketplace-nav />

    <!-- Main Content -->
    <main class="pt-12">
        {{ $slot }}
    </main>

    @include('components.cart-sidebar')
    @include('modals.login')
    
    <script>
        // Currency switching function
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
                    location.reload(); // Reload to show new prices
                }
            })
            .catch(error => console.error('Error setting currency:', error));
        }
    </script>
</body>
</html>