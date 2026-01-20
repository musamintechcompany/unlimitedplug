<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Products</p>
            <p class="text-3xl font-bold text-gray-900" x-data="{ count: {{ \App\Models\Product::count() }} }" x-text="count" @product-created.window="count++" @product-deleted.window="count--"></p>
        </div>
        <div>
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>
    </div>
</div>
