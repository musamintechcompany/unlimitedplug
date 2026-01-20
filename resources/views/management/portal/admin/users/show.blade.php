<x-admin.app-layout>
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
        <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">User Information</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl font-medium text-gray-700">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ '@' . $user->username }}</p>
                            <span class="mt-2 inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($user->status === 'active') bg-green-100 text-green-800
                                @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($user->status === 'suspended') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Email</td>
                                <td class="py-3 text-sm text-gray-900">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Username</td>
                                <td class="py-3 text-sm text-gray-900">{{ '@' . $user->username }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Email Verified</td>
                                <td class="py-3 text-sm text-gray-900">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">✓ Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-red-600">✗ Not Verified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Joined</td>
                                <td class="py-3 text-sm text-gray-900">{{ $user->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Last Updated</td>
                                <td class="py-3 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Products ({{ $user->products->count() }})</h2>
                </div>
                <div class="overflow-x-auto">
                    @if($user->products->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->products as $product)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($product->type) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($product->status === 'published') bg-green-100 text-green-800
                                            @elseif($product->status === 'draft') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-8 text-center text-gray-500">
                            No products created yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Purchase History Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Purchase History ({{ $user->orders->count() }})</h2>
                </div>
                <div class="overflow-x-auto">
                    @if($user->orders->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->orders as $order)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->orderItems->count() }} item(s)</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $order->currency }} {{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-8 text-center text-gray-500">
                            No purchases yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">Total Products</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $user->products->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Total Orders</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $user->orders->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Total Spent</div>
                        <div class="text-2xl font-bold text-gray-900">
                            @php
                                $totalSpent = $user->orders->where('status', 'completed')->sum('total_amount');
                            @endphp
                            {{ $user->orders->first()->currency ?? 'NGN' }} {{ number_format($totalSpent, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <button onclick="editUser('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->status }}')" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Edit User
                    </button>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
            </div>
            <form id="editUserForm" method="POST">
                @csrf @method('PUT')
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="editName" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="editEmail" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="editStatus" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditUserModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(id, name, email, status) {
    document.getElementById('editUserForm').action = `/management/portal/admin/users/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editStatus').value = status;
    document.getElementById('editUserModal').classList.remove('hidden');
}

function closeEditUserModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}
</script>
</x-admin.app-layout>
