<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-2xl text-gray-800 mb-6 px-4 sm:px-0">
                My Purchases
            </h2>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">You haven't made any purchases yet.</p>
                            <a href="{{ route('marketplace') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                                Browse Marketplace
                            </a>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="border rounded-lg overflow-hidden">
                                    <!-- Order Header -->
                                    <div class="bg-gray-50 px-6 py-4 border-b">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h3 class="font-bold text-lg text-gray-900">Order #{{ $order->order_number }}</h3>
                                                @php
                                                    $currencySymbol = config('payment.currencies.' . $order->currency . '.symbol', '$');
                                                @endphp
                                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }} • {{ $currencySymbol }}{{ number_format($order->total_amount, 2) }} {{ $order->currency }} • {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</p>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="divide-y">
                                        @foreach($order->items as $item)
                                            <div class="p-6 hover:bg-gray-50 transition">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Thumbnail -->
                                                    <div class="flex-shrink-0">
                                                        @if($item->product && $item->product->banner)
                                                            <img src="{{ Storage::url($item->product->banner) }}" 
                                                                 alt="{{ $item->product_name }}" 
                                                                 class="w-20 h-20 object-cover rounded border">
                                                        @else
                                                            <div class="w-20 h-20 bg-gray-200 rounded border flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Item Details -->
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $item->product_name }}</h4>
                                                        @php
                                                            $currencySymbol = config('payment.currencies.' . $order->currency . '.symbol', '$');
                                                        @endphp
                                                        <p class="text-sm text-gray-600">{{ $currencySymbol }}{{ number_format($item->price, 2) }} {{ $order->currency }}</p>
                                                        <!-- Mobile Link -->
                                                        @if($item->product_id)
                                                            <a href="{{ route('purchases.show', $item->product_id) }}" 
                                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block sm:hidden">
                                                                View Details →
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <!-- Desktop Button -->
                                                    <div class="hidden sm:block flex-shrink-0">
                                                        @if($item->product_id)
                                                            <a href="{{ route('purchases.show', $item->product_id) }}" 
                                                               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                                                View Details
                                                            </a>
                                                        @else
                                                            <span class="text-gray-400 text-sm">Unavailable</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
