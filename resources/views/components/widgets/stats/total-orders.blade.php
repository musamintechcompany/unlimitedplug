<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Orders</p>
            <p class="text-3xl font-bold text-gray-900" x-data="{ count: {{ \App\Models\Order::count() }} }" x-text="count" @order-created.window="count++"></p>
        </div>
        <div>
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </div>
</div>
