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
class="bg-white border-b border-gray-100 fixed top-0 w-full z-40 transition-transform duration-300" 
:class="{ '-translate-y-full': !showNav, 'translate-y-0': showNav }">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-12">
            <div class="flex items-center">
                <!-- Mobile Hamburger -->
                <button @click="open = ! open; $dispatch('sidebar-toggle', { open: open })" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out sm:hidden mr-3">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-6 w-auto fill-current text-gray-800" />
                        <span class="ml-2 text-lg font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('marketplace')" :active="request()->routeIs('marketplace')">
                        {{ __('Marketplace') }}
                    </x-nav-link>
                    <x-nav-link :href="route('purchases.index')" :active="request()->routeIs('purchases.index')">
                        {{ __('My Purchases') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Cart & Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Cart Icon -->
                <button onclick="toggleCartSidebar()" class="relative text-gray-500 hover:text-gray-700 p-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center border border-transparent rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full border-2 border-gray-300">
                            @else
                                <div class="w-8 h-8 rounded-full border-2 border-gray-300 bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Cart & User Profile -->
            <div class="-me-2 flex items-center space-x-2 sm:hidden">
                <button onclick="toggleCartSidebar()" class="relative text-gray-500 hover:text-gray-700 p-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                </button>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center border border-transparent rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full border-2 border-gray-300">
                            @else
                                <div class="w-8 h-8 rounded-full border-2 border-gray-300 bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

@include('user.sidebar')
</nav>