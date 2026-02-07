<nav x-data="{ 
    open: false, 
    lastScrollY: 0, 
    showNav: true 
}" 
@scroll.window="
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
        showNav = false;
    } else if (window.scrollY < lastScrollY) {
        showNav = true;
    }
    lastScrollY = window.scrollY;
" 
class="bg-white/95 backdrop-blur-sm border-b border-gray-200 fixed top-0 w-full z-40 transition-transform duration-300" 
:class="{ '-translate-y-full': !showNav, 'translate-y-0': showNav }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-12">
            <!-- Logo -->
            <div class="flex items-center">
                <!-- Mobile Hamburger -->
                <button @click="open = !open; $dispatch('sidebar-toggle', { open: open })" class="p-2 rounded-lg text-gray-700 hover:bg-gray-100 md:hidden mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <a href="/" class="flex items-center hover:opacity-80 transition-opacity">
                    <x-application-logo class="h-8 w-8 sm:h-10 sm:w-10 mr-2 sm:mr-3 flex-shrink-0" />
                    <span class="text-sm sm:text-lg font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent whitespace-nowrap">Unlimited Plug</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/marketplace" class="text-blue-600 font-medium">MarketPlace</a>
            </div>

            <!-- Currency Selector -->
            <div class="hidden md:flex items-center">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 transition-colors px-3 py-1 rounded-lg hover:bg-gray-50">
                        <span id="selected-currency">{{ session('currency', 'USD') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 max-h-96 overflow-y-auto">
                        @foreach(config('payment.currencies') as $code => $currency)
                            <button onclick="setCurrency('{{ $code }}')" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                {{ $code }} - {{ $currency['symbol'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Auth Buttons & Cart -->
            <div class="hidden md:flex items-center space-x-4">
                <button onclick="toggleCartSidebar()" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                </button>
                @auth
                    <button onclick="toggleUserNotifications()" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span id="user-notification-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Dashboard</a>
                @else
                    <button @click="$dispatch('open-login-modal')" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Login</span>
                    </button>
                @endauth
            </div>

            <!-- Mobile Currency & Cart -->
            <div class="md:hidden flex items-center space-x-2">
                <!-- Mobile Currency Selector -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 transition-colors px-2 py-1 rounded-lg">
                        <span class="text-sm" id="selected-currency-mobile">{{ session('currency', 'USD') }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 max-h-64 overflow-y-auto">
                        @foreach(config('payment.currencies') as $code => $currency)
                            <button onclick="setCurrency('{{ $code }}')" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                {{ $code }}
                            </button>
                        @endforeach
                    </div>
                </div>
                
                @guest
                    <button @click="$dispatch('open-login-modal')" class="text-gray-700 hover:text-blue-600 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                @endguest
                
                @auth
                    <button onclick="toggleUserNotifications()" class="relative text-gray-700 hover:text-blue-600 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span id="user-notification-count-mobile" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                    </button>
                @endauth
                
                <button onclick="toggleCartSidebar()" class="relative text-gray-700 hover:text-blue-600 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                </button>
            </div>
        </div>

    </div>

@include('components.layouts.marketplace-sidebar')
</nav>