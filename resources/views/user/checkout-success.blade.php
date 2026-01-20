<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
                    <p class="text-gray-600">Thank you for your purchase. Your order has been processed successfully.</p>
                </div>

                @if($order)
                    <!-- Order Details -->
                    <div class="border-t border-b py-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-semibold text-gray-900">{{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <!-- Purchased Items -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Items Purchased</h3>
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                        @if($item->product && $item->product->banner)
                                            <img src="{{ Storage::url($item->product->banner) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                            <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                        <p class="font-semibold text-gray-900">{{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-3">
                    @if($order && $order->items->isNotEmpty())
                        <a href="{{ route('purchases.show', $order->items->first()->product_id) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold text-center">
                            Download Your Purchase
                        </a>
                    @else
                        <a href="{{ route('purchases.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold text-center">
                            View My Purchases
                        </a>
                    @endif
                    <a href="{{ route('marketplace') }}" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold text-center">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>