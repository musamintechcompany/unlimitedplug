@props(['period' => 30])

@php
    // Handle custom date range
    if ($period === 'custom' && request('start_date') && request('end_date')) {
        $startDate = \Carbon\Carbon::parse(request('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse(request('end_date'))->endOfDay();
    } else {
        $startDate = now()->subDays($period)->startOfDay();
        $endDate = now()->endOfDay();
    }
    
    $orderBalances = \App\Models\Order::where('status', 'completed')
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->selectRaw('currency, SUM(total_amount) as total')
        ->groupBy('currency')
        ->pluck('total', 'currency');
    
    $allCurrencies = config('payment.currencies');
    
    $currencyBalances = collect($allCurrencies)->map(function ($currencyInfo, $code) use ($orderBalances) {
        return (object) [
            'currency' => $code,
            'total' => $orderBalances->get($code, 0)
        ];
    });
@endphp

<div class="mb-8">
    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900">Currency Balances</h2>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-3">
                <select name="period" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 Days</option>
                    <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last 365 Days</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
                <div id="custom-date-inputs" class="flex gap-2" style="{{ $period != 'custom' ? 'display: none;' : '' }}">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Apply</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($currencyBalances as $balance)
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition">
                <p class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 break-all">{{ config('payment.currencies.' . $balance->currency . '.symbol') }}{{ number_format($balance->total, 2) }}</p>
                <p class="text-sm text-gray-600">Total Volume ({{ $balance->currency }})</p>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.querySelector('select[name="period"]');
    const customInputs = document.getElementById('custom-date-inputs');
    
    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customInputs.style.display = 'flex';
        } else {
            customInputs.style.display = 'none';
            this.form.submit();
        }
    });
});
</script>
