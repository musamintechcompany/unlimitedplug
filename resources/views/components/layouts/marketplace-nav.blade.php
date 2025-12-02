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
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <a href="/" class="flex items-center hover:opacity-80 transition-opacity">
                    <x-application-logo class="h-8 w-auto mr-3" />
                    <span class="text-lg font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">Unlimited Plug</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a>
                <a href="/marketplace" class="text-blue-600 font-medium">MarketPlace</a>
            </div>

            <!-- Currency Selector -->
            <div class="hidden md:flex items-center">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 transition-colors px-3 py-1 rounded-lg hover:bg-gray-50">
                        <span id="selected-currency">{{ session('currency', 'NGN') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-24 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <button onclick="setCurrency('USD')" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">USD</button>
                        <button onclick="setCurrency('NGN')" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">NGN</button>
                    </div>
                </div>
            </div>

            <!-- Auth Buttons & Cart -->
            <div class="hidden md:flex items-center space-x-4">
                <button onclick="toggleCartSidebar()" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Dashboard</a>
                @else
                    <button @click="$dispatch('open-login-modal')" class="text-gray-700 hover:text-blue-600 transition-colors">
                        Login
                    </button>
                @endauth
            </div>

            <!-- Mobile Cart -->
            <div class="md:hidden flex items-center">
                <button onclick="toggleCartSidebar()" class="relative text-gray-700 hover:text-blue-600 p-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                </button>
            </div>
        </div>

    </div>

@include('components.layouts.marketplace-sidebar')
</nav>

<!-- Login Modal -->
@include('modals.login')