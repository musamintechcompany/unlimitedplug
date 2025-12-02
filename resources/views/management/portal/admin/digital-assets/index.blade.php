<x-admin.app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Digital Assets Management</h1>
            <p class="text-gray-600 mt-2">Manage and review user-submitted digital assets</p>
        </div>
        <a href="{{ route('admin.digital-assets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            Create Asset
        </a>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('admin.digital-assets.index') }}" 
           class="px-4 py-2 {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded">
            All ({{ \App\Models\DigitalAsset::count() }})
        </a>
        <a href="{{ route('admin.digital-assets.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 {{ request('status') === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded">
            Pending ({{ \App\Models\DigitalAsset::where('status', 'pending')->count() }})
        </a>
        <a href="{{ route('admin.digital-assets.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded">
            Approved ({{ \App\Models\DigitalAsset::where('status', 'approved')->count() }})
        </a>
        <a href="{{ route('admin.digital-assets.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded">
            Rejected ({{ \App\Models\DigitalAsset::where('status', 'rejected')->count() }})
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Assets Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assets as $asset)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($asset->media && count($asset->media) > 0)
                                        <img class="h-12 w-12 rounded object-cover" src="{{ Storage::url($asset->media[0]) }}" alt="{{ $asset->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $asset->subcategory }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $asset->user ? $asset->user->name : 'Admin Created' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">
                                    {{ ucfirst($asset->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ $asset->price }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($asset->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-500 text-white">
                                @elseif($asset->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-500 text-white">
                                @elseif($asset->status === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-500 text-white">
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-500 text-white">
                                @endif
                                    {{ ucfirst($asset->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $asset->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.digital-assets.show', $asset) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">View</a>
                                    <a href="{{ route('admin.digital-assets.edit', $asset) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Edit</a>
                                    <form action="{{ route('admin.digital-assets.destroy', $asset) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs" 
                                                onclick="return confirm('Are you sure you want to delete this asset? This action cannot be undone.')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No digital assets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4">
            {{ $assets->links() }}
        </div>
    </div>
</div>
</x-admin.app-layout>