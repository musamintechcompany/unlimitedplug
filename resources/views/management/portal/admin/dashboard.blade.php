<x-admin.app-layout>
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome back, {{ auth()->guard('admin')->user()->name }}!</p>
            </div>
            <a href="{{ route('admin.products.select-type') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition text-center">
                + Create Product
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-widgets.stats.total-users />
        <x-widgets.stats.total-products />
        <x-widgets.stats.pending-products />
        <x-widgets.stats.approved-products />
        <x-widgets.stats.rejected-products />
        <x-widgets.stats.total-categories />
        <x-widgets.stats.total-orders />
        <x-widgets.stats.total-visitors />
    </div>
    
    <!-- Currency Balances -->
    <x-widgets.stats.currency-balances :period="$period" />
    
    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-widgets.charts.simple-line-chart />
        <x-widgets.charts.visitor-line-chart />
    </div>
</div>
</x-admin.app-layout>