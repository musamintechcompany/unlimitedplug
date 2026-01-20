<x-admin.app-layout>
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Products Management</h1>
                <p class="text-gray-600 mt-2">Manage and review products</p>
            </div>
            <a href="{{ route('admin.products.select-type') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition text-center">
                + Create Product
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-widgets.stats.total-products />
        <x-widgets.stats.pending-products />
        <x-widgets.stats.approved-products />
        <x-widgets.stats.rejected-products />
    </div>

    <!-- Search and Filter -->
    <div id="filters" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <form method="GET" action="{{ route('admin.products.index') }}#filters">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white flex items-center justify-between gap-2">
                <span>{{ request('status') ? ucfirst(request('status')) : 'All Status' }}</span>
                <svg class="w-4 h-4 text-gray-600 transition-transform duration-200" :class="open ? 'rotate-90' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute left-0 sm:right-0 sm:left-auto mt-2 w-full sm:w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                <a href="{{ route('admin.products.index', ['search' => request('search')]) }}#filters" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Status</a>
                <a href="{{ route('admin.products.index', ['status' => 'pending', 'search' => request('search')]) }}#filters" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pending</a>
                <a href="{{ route('admin.products.index', ['status' => 'approved', 'search' => request('search')]) }}#filters" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Approved</a>
                <a href="{{ route('admin.products.index', ['status' => 'rejected', 'search' => request('search')]) }}#filters" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rejected</a>
            </div>
        </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assets as $asset)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    @if($asset->banner)
                                        <img class="h-12 w-12 rounded object-cover flex-shrink-0" src="{{ Storage::url($asset->banner) }}" alt="{{ $asset->name }}">
                                    @elseif($asset->media && count($asset->media) > 0)
                                        <img class="h-12 w-12 rounded object-cover flex-shrink-0" src="{{ Storage::url($asset->media[0]) }}" alt="{{ $asset->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $asset->name }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ $asset->subcategory ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-200">
                                <div class="text-sm text-gray-900 truncate max-w-[150px]">
                                    {{ $asset->user ? $asset->user->name : 'Admin Created' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">
                                    {{ ucfirst($asset->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                ${{ $asset->price }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-200">
                                {{ $asset->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.products.show', $asset) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">View</a>
                                    <a href="{{ route('admin.products.edit', $asset) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $asset) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs" 
                                                onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No products found.
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