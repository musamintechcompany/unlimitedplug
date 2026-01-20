<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-900" x-data="{ revenue: {{ \App\Models\Order::where('payment_status', 'completed')->sum('total_amount') }} }" x-text="'$' + revenue.toFixed(2)" @order-completed.window="revenue += parseFloat($event.detail.amount)"></p>
        </div>
        <div>
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>
