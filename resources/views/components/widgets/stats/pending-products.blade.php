<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Pending Products</p>
            <p class="text-3xl font-bold text-gray-900" x-data="{ count: {{ \App\Models\Product::where('status', 'pending')->count() }} }" x-text="count" @product-status-changed.window="if($event.detail.status === 'pending') count++; else count--"></p>
        </div>
        <div>
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>
