<nav x-data="{ 
    open: false, 
    lastScrollY: 0, 
    showNav: true 
}" 
@open-changed.window="$dispatch('sidebar-toggle', { open: open })" 
@scroll.window="
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
        showNav = false;
    } else if (window.scrollY < lastScrollY) {
        showNav = true;
    }
    lastScrollY = window.scrollY;
" 
class="bg-white border-b border-gray-100 fixed top-0 w-full z-40 transition-transform duration-300" 
:class="{ '-translate-y-full': !showNav, 'translate-y-0': showNav }">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-12">
            <!-- Left: Hamburger + Logo -->
            <div class="flex items-center space-x-3">
                <!-- Hamburger (Mobile) -->
                <button @click="open = ! open; $dispatch('sidebar-toggle', { open: open })" class="sm:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-5 w-5 sm:h-6 sm:w-6 fill-current text-gray-800 flex-shrink-0" />
                        <span class="ml-2 text-sm sm:text-lg font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent whitespace-nowrap">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.coupons.index')" :active="request()->routeIs('admin.coupons.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Coupons') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.payments.index')" :active="request()->routeIs('admin.payments.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Payment History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Reviews') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Reports') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right: Bell + Profile -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Notification Bell -->
                <button onclick="toggleAdminNotifications()" class="relative text-gray-500 hover:text-gray-700 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span id="admin-notification-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 hidden items-center justify-center">0</span>
                </button>
                
                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="inline-flex items-center p-1 sm:px-3 sm:py-1">
                        @if(auth()->guard('admin')->user()->profile_photo_path)
                            <img src="{{ auth()->guard('admin')->user()->profile_photo_path }}" alt="{{ auth()->guard('admin')->user()->name }}" class="w-8 h-8 rounded-full border-2 border-gray-600 sm:mr-2">
                        @else
                            <div class="w-8 h-8 rounded-full border-2 border-gray-600 bg-blue-500 flex items-center justify-center text-white text-xs font-semibold sm:mr-2">
                                {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="hidden sm:inline text-sm font-medium text-gray-500">{{ auth()->guard('admin')->user()->name }}</span>
                        <svg class="hidden sm:inline fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->guard('admin')->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->guard('admin')->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('management.portal.admin.sidebar')

<script>
    // Fetch notification count on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchNotificationCount();
        
        // Poll for new notifications every 30 seconds
        setInterval(fetchNotificationCount, 30000);
    });
    
    function fetchNotificationCount() {
        fetch('{{ route('admin.notifications.count') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('admin-notification-count');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
    }
</script>
</nav>