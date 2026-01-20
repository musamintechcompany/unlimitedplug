<x-admin.app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Coupons</h1>
            <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Coupon
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valid Until</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="px-6 py-4 font-mono font-bold">{{ $coupon->code }}</td>
                            <td class="px-6 py-4">{{ ucfirst($coupon->type) }}</td>
                            <td class="px-6 py-4">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    ${{ number_format($coupon->value, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</td>
                            <td class="px-6 py-4">{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'No expiry' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="text-green-600 hover:text-green-800">View</a>
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No coupons found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    </div>
</x-admin.app-layout>
