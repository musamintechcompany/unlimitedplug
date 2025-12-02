<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Digital Assets
            </h2>
            <a href="{{ route('digital-assets.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Create New Asset
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($assets->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($assets as $asset)
                                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                    <div class="relative">
                                        <img src="{{ Storage::url($asset->preview_image) }}" 
                                             alt="{{ $asset->title }}" 
                                             class="w-full h-40 object-cover">
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-1 text-xs font-semibold rounded 
                                                @if($asset->status === 'approved') bg-green-100 text-green-800
                                                @elseif($asset->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($asset->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($asset->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs font-semibold text-blue-600 uppercase">
                                                {{ $asset->type }}
                                            </span>
                                            <span class="text-lg font-bold text-green-600">
                                                ${{ $asset->price }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $asset->title }}
                                        </h3>
                                        
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                            {{ Str::limit($asset->description, 100) }}
                                        </p>
                                        
                                        <div class="flex justify-between items-center text-sm text-gray-500">
                                            <span>{{ $asset->downloads }} downloads</span>
                                            <span>{{ $asset->created_at->format('M j, Y') }}</span>
                                        </div>
                                        
                                        <div class="mt-4 flex space-x-2">
                                            <a href="{{ route('digital-assets.show', $asset) }}" 
                                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm">
                                                View
                                            </a>
                                            <a href="{{ route('digital-assets.edit', $asset) }}" 
                                               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-3 rounded text-sm">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $assets->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📦</div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                No digital assets yet
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Start creating and selling your digital products.
                            </p>
                            <a href="{{ route('digital-assets.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md">
                                Create Your First Asset
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>