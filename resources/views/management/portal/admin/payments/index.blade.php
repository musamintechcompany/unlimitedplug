<x-admin.app-layout>
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Payment History</h1>
            <p class="text-gray-600">View all payment transactions</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @if($payments->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    No payments yet
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->payment_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        @php
                                            $currencySymbol = config('payment.currencies.' . $payment->currency . '.symbol', '$');
                                        @endphp
                                        {{ $currencySymbol }}{{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @php
                                            $order = $payment->user ? $payment->user->orders()->where('payment_id', $payment->id)->first() : null;
                                            $paymentMethod = $order ? ucfirst($order->payment_method) : 'N/A';
                                        @endphp
                                        {{ $paymentMethod }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->status === 'confirmed')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmed</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.app-layout>
