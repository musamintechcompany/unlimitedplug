<!-- Mobile Overlay -->
<div x-show="open" @click="open = false; $dispatch('sidebar-toggle', { open: false })" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-50 sm:hidden" style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important;"></div>

<!-- Mobile Sidebar -->
<div x-show="open" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed w-64 bg-white z-[60] sm:hidden shadow-xl flex flex-col" style="position: fixed !important; top: 0 !important; left: 0 !important; height: 100vh !important;">
    <div class="p-6 flex items-center">
        <x-application-logo class="h-8 w-auto fill-current text-gray-800" />
        <span class="ml-2 text-xl font-bold text-gray-800 truncate">{{ config('app.name') }}</span>
    </div>
    <nav class="mt-6 flex-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            Users
        </a>
        <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.products.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 6.707 6.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l4-4z"></path>
            </svg>
            Products
        </a>
        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.categories.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 6.707 6.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Categories
        </a>
        <a href="{{ route('admin.payments.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.payments.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
            </svg>
            Payment History
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reviews.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            Reviews
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.coupons.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd"></path>
                <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"></path>
            </svg>
            Coupons
        </a>
        <a href="{{ route('admin.reports.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.*') ? 'text-gray-700 bg-gray-100 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"></path>
            </svg>
            Reports
        </a>
    </nav>
    
    <!-- Admin Profile Section - Sticky to Bottom -->
    <div class="border-t border-gray-200 p-6 bg-gray-50">
        <div class="flex items-center">
            @if(auth()->guard('admin')->user()->profile_photo_path)
                <img src="{{ auth()->guard('admin')->user()->profile_photo_path }}" alt="{{ auth()->guard('admin')->user()->name }}" class="w-10 h-10 rounded-full border-2 border-gray-600 mr-3 flex-shrink-0">
            @else
                <div class="w-10 h-10 rounded-full border-2 border-gray-600 mr-3 bg-blue-500 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                    {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 2)) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <div class="font-medium text-base text-gray-800 truncate">{{ auth()->guard('admin')->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 truncate">{{ auth()->guard('admin')->user()->email }}</div>
            </div>
        </div>
    </div>
</div>