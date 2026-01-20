<x-admin.app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Coupon Details</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Edit Coupon
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Coupon Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Coupon Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-600">Code</label>
                    <p class="text-lg font-mono font-bold">{{ $coupon->code }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <p>
                        <span class="px-3 py-1 text-sm rounded {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Type</label>
                    <p class="text-lg">{{ ucfirst($coupon->type) }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Discount Value</label>
                    <p class="text-lg font-semibold">
                        @if($coupon->type === 'percentage')
                            {{ $coupon->value }}%
                        @else
                            ${{ number_format($coupon->value, 2) }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Minimum Purchase</label>
                    <p class="text-lg">{{ $coupon->min_purchase ? '$' . number_format($coupon->min_purchase, 2) : 'No minimum' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Max Uses</label>
                    <p class="text-lg">{{ $coupon->max_uses ?? 'Unlimited' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Max Uses Per User</label>
                    <p class="text-lg">{{ $coupon->max_uses_per_user }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Valid From</label>
                    <p class="text-lg">{{ $coupon->valid_from ? $coupon->valid_from->format('M d, Y H:i') : 'Immediately' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Valid Until</label>
                    <p class="text-lg">{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y H:i') : 'No expiry' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Created</label>
                    <p class="text-lg">{{ $coupon->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 mb-1">Total Uses</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_uses'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 mb-1">Unique Users</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['unique_users'] }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 mb-1">Total Discount Given</p>
                <p class="text-3xl font-bold text-purple-600">${{ number_format($stats['total_discount_given'], 2) }}</p>
            </div>
            <div class="bg-orange-50 rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 mb-1">Revenue Generated</p>
                <p class="text-3xl font-bold text-orange-600">${{ number_format($stats['revenue_generated'], 2) }}</p>
            </div>
        </div>

        <!-- Usage History -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Usage History</h2>
            </div>
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coupon->usages as $usage)
                        <tr>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium">{{ $usage->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $usage->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm">{{ Str::limit($usage->order_id, 8, '') }}</td>
                            <td class="px-6 py-4">${{ number_format($usage->order_total, 2) }}</td>
                            <td class="px-6 py-4 text-green-600 font-semibold">-${{ number_format($usage->discount_amount, 2) }}</td>
                            <td class="px-6 py-4">{{ $usage->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <p class="text-lg">No usage history yet</p>
                                <p class="text-sm mt-1">This coupon hasn't been used by any customers</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin.app-layout>
