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
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-6 w-auto fill-current text-gray-800" />
                        <span class="ml-2 text-lg font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ config('app.name') }}</span>
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
                    <x-nav-link :href="route('admin.payments.index')" :active="request()->routeIs('admin.payments.*')" class="text-gray-600 hover:text-gray-900">
                        {{ __('Payment History') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @if(auth()->guard('admin')->user()->profile_photo_path)
                                <img src="{{ auth()->guard('admin')->user()->profile_photo_path }}" alt="{{ auth()->guard('admin')->user()->name }}" class="w-8 h-8 rounded-full border-2 border-gray-600 mr-2">
                            @else
                                <div class="w-8 h-8 rounded-full border-2 border-gray-600 mr-2 bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>{{ auth()->guard('admin')->user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('admin.profile')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('admin.logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open; $dispatch('sidebar-toggle', { open: open })" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

@include('management.portal.admin.sidebar')
</nav>