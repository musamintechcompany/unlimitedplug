<!-- Mobile Overlay -->
<div x-show="open" @click="open = false; $dispatch('sidebar-toggle', { open: false })" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-50 sm:hidden" style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important;"></div>

<!-- Mobile Sidebar -->
<div x-show="open" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed w-64 bg-white z-[60] sm:hidden shadow-xl flex flex-col" style="position: fixed !important; top: 0 !important; left: 0 !important; height: 100vh !important;">
    <div class="p-6 flex items-center">
        <x-application-logo class="h-8 w-auto" />
        <span class="ml-2 text-xl font-bold text-gray-800 truncate">{{ config('app.name') }}</span>
    </div>
    <nav class="mt-6 flex-1">
        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100 border-r-4 border-blue-500">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('marketplace') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 6.707 6.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l4-4z"></path>
            </svg>
            Marketplace
        </a>

        <a href="{{ route('profile.edit') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            Profile
        </a>
    </nav>
    
    <!-- User Profile Section - Sticky to Bottom -->
    <div class="border-t border-gray-200 p-6 bg-gray-50">
        <div class="flex items-center">
            @if(Auth::user()->profile_photo)
                <img src="{{ Auth::user()->profile_photo }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full border-2 border-gray-300 mr-3 flex-shrink-0">
            @else
                <div class="w-10 h-10 rounded-full border-2 border-gray-300 mr-3 bg-blue-500 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <div class="font-medium text-base text-gray-800 truncate">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>
</div>