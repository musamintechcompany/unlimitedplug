<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Suspended Users</p>
            <p class="text-3xl font-bold text-gray-900" x-data="{ count: {{ \App\Models\User::where('status', 'suspended')->count() }} }" x-text="count"></p>
        </div>
        <div>
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
            </svg>
        </div>
    </div>
</div>
