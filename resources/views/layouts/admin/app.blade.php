<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin Panel - {{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        
        <script>
            window.reverbConfig = {
                key: '{{ config('broadcasting.connections.reverb.key') }}',
                host: '{{ config('broadcasting.connections.reverb.options.host') }}',
                port: {{ config('broadcasting.connections.reverb.options.port') }},
                scheme: '{{ config('broadcasting.connections.reverb.options.scheme') }}'
            };
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            @include('management.portal.admin.navigation')

            <!-- Page Content -->
            <main class="pt-12">
                {{ $slot }}
            </main>
        </div>
        
        @include('management.portal.admin.notifications.index')

<script type="module">
    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';
    
    window.Pusher = Pusher;
    
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: window.reverbConfig.key,
        wsHost: window.reverbConfig.host,
        wsPort: window.reverbConfig.port,
        wssPort: window.reverbConfig.port,
        forceTLS: window.reverbConfig.scheme === 'https',
        enabledTransports: ['ws', 'wss'],
    });
    
    // Listen for new notifications
    window.Echo.channel('admin-notifications')
        .listen('.notification.created', (e) => {
            console.log('New notification received:', e);
            fetchNotificationCount();
            
            // If sidebar is open, reload notifications
            const sidebar = document.getElementById('admin-notification-sidebar');
            if (sidebar && !sidebar.classList.contains('translate-x-full')) {
                loadAdminNotifications();
            }
        });
    
    // Listen for analytics updates
    window.Echo.channel('admin-analytics')
        .listen('.analytics.updated', () => {
            console.log('Analytics updated');
            if (typeof loadChartData === 'function') {
                loadChartData();
            }
        });
</script>
    </body>
</html>