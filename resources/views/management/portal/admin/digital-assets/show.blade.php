<x-admin.app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.digital-assets.index') }}" 
           class="text-blue-600 hover:text-blue-800">&larr; Back to Digital Assets</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Asset Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                @php
                    $allImages = [];
                    if($digitalAsset->banner) $allImages[] = $digitalAsset->banner;
                    if($digitalAsset->media) $allImages = array_merge($allImages, $digitalAsset->media);
                @endphp
                
                @if(count($allImages) > 0)
                    <div class="mb-6" x-data="{ activeImage: '{{ Storage::url($allImages[0]) }}' }">
                        <!-- Main Image -->
                        <img :src="activeImage" 
                             alt="{{ $digitalAsset->name }}" 
                             class="w-full h-96 object-contain bg-gray-100 rounded mb-4">
                        
                        <!-- Thumbnails -->
                        @if(count($allImages) > 1)
                            <div class="flex space-x-2 overflow-x-auto">
                                @foreach($allImages as $image)
                                    <img src="{{ Storage::url($image) }}" 
                                         alt="{{ $digitalAsset->name }}" 
                                         class="w-16 h-16 object-cover rounded cursor-pointer border-2 hover:border-blue-500"
                                         :class="{ 'border-blue-500': activeImage === '{{ Storage::url($image) }}', 'border-gray-300': activeImage !== '{{ Storage::url($image) }}' }"
                                         @click="activeImage = '{{ Storage::url($image) }}'">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $digitalAsset->name }}</h1>
                
                <div class="flex items-center space-x-4 mb-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded">
                        {{ ucfirst($digitalAsset->type) }}
                    </span>
                    <span class="text-gray-600">{{ $digitalAsset->subcategory }}</span>
                    <div class="flex items-center space-x-2">
                        @if($digitalAsset->list_price && $digitalAsset->list_price > $digitalAsset->price)
                            <span class="text-lg text-gray-500 line-through">${{ $digitalAsset->list_price }}</span>
                        @endif
                        <span class="text-2xl font-bold text-green-600">${{ $digitalAsset->price }}</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700">{{ $digitalAsset->description }}</p>
                </div>
                
                @if($digitalAsset->features)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Features</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($digitalAsset->features as $feature)
                                <li class="text-gray-700">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if($digitalAsset->tags)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($digitalAsset->tags as $tag)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-sm rounded">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($digitalAsset->requirements)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Requirements</h3>
                        <p class="text-gray-700">{{ $digitalAsset->requirements }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Asset Information -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Asset Information</h2>
                
                <div class="space-y-3 text-sm">
                    <div><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $digitalAsset->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $digitalAsset->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $digitalAsset->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $digitalAsset->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($digitalAsset->status) }}
                        </span>
                    </div>
                    <div><strong>Featured:</strong> {{ $digitalAsset->is_featured ? 'Yes' : 'No' }}</div>
                    <div><strong>Author:</strong> {{ $digitalAsset->user ? $digitalAsset->user->name : 'Admin Created' }}</div>
                    <div><strong>Created:</strong> {{ $digitalAsset->created_at->format('M j, Y g:i A') }}</div>
                    <div><strong>Downloads:</strong> {{ $digitalAsset->downloads }}</div>
                    <div><strong>Rating:</strong> {{ $digitalAsset->rating ?? 'No rating' }}/5 ({{ $digitalAsset->reviews_count ?? 0 }} reviews)</div>
                    @if($digitalAsset->demo_url)
                        <div><strong>Demo:</strong> <a href="{{ $digitalAsset->demo_url }}" target="_blank" class="text-blue-600 hover:underline">View Demo</a></div>
                    @endif
                </div>
                

            </div>
        </div>
    </div>
</div>
</x-admin.app-layout>