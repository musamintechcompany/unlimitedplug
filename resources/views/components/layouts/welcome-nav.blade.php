<!-- Navigation -->
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
class="w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 z-40 transition-transform duration-300 fixed top-0" 
:class="{ '-translate-y-full': !showNav, 'translate-y-0': showNav }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <!-- Mobile Hamburger -->
                <button @click="open = !open; $dispatch('sidebar-toggle', { open: open })" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-2 md:hidden mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <a href="{{ url('/') }}" class="flex items-center">
                    <x-application-logo class="h-8 w-auto mr-3" />
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">
                        Unlimited Plug
                    </h1>
                </a>
            </div>
            
            @if (Route::has('login'))
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors pb-1">
                        Home
                    </a>
                    <a href="{{ route('marketplace') }}" class="{{ request()->is('marketplace*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors pb-1">
                        MarketPlace
                    </a>
                    <a href="{{ route('how-it-works') }}" class="{{ request()->is('how-it-works') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors pb-1">
                        How It Works
                    </a>
                    <button onclick="toggleCartSidebar()" class="relative text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                        <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Dashboard
                        </a>
                    @else
                        <button @click="$dispatch('open-login-modal')" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                            Log in
                        </button>
                    @endauth
                </div>
                
                <!-- Mobile Cart -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleCartSidebar()" class="relative text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                        <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    
@include('components.layouts.welcome-sidebar')
</nav>