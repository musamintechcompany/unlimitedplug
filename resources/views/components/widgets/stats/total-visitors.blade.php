@php
    $period = request()->input('period', 30);
    $startDate = now()->subDays($period);
    
    $totalVisitors = \App\Models\Visitor::where('created_at', '>=', $startDate)->count();
    $uniqueVisitors = \App\Models\Visitor::where('created_at', '>=', $startDate)->distinct('visitor_id')->count('visitor_id');
@endphp

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm font-medium text-gray-600">Total Visitors</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalVisitors) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($uniqueVisitors) }} unique</p>
        </div>
        <div class="p-3 bg-purple-100 rounded-lg">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </div>
    
    <div class="flex items-center justify-between">
        <select onchange="window.location.href='?period='+this.value" class="text-xs border border-gray-300 rounded px-2 py-1">
            <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
            <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
            <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
            <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
        </select>
    </div>
</div>
