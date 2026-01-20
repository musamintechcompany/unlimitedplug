<x-admin.app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ showDownloadsModal: false, showPurchasesModal: false }">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" 
           class="text-blue-600 hover:text-blue-800">&larr; Back to Products</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Asset Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                @php
                    $allImages = [];
                    if($product->banner) $allImages[] = $product->banner;
                    if($product->media) $allImages = array_merge($allImages, $product->media);
                @endphp
                
                @if(count($allImages) > 0)
                    <div class="mb-6" x-data="{ activeImage: '{{ Storage::url($allImages[0]) }}' }">
                        <!-- Main Image -->
                        <img :src="activeImage" 
                             alt="{{ $product->name }}" 
                             class="w-full h-96 object-contain bg-gray-100 rounded mb-4">
                        
                        <!-- Thumbnails -->
                        @if(count($allImages) > 1)
                            <div class="flex space-x-2 overflow-x-auto">
                                @foreach($allImages as $image)
                                    <img src="{{ Storage::url($image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-16 h-16 object-cover rounded cursor-pointer border-2 hover:border-blue-500"
                                         :class="{ 'border-blue-500': activeImage === '{{ Storage::url($image) }}', 'border-gray-300': activeImage !== '{{ Storage::url($image) }}' }"
                                         @click="activeImage = '{{ Storage::url($image) }}'">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
                
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <table class="w-full text-sm">
                        <tr class="border-b border-gray-200">
                            <td class="py-2 font-semibold text-gray-700 w-1/3">Type:</td>
                            <td class="py-2 text-gray-900">{{ ucfirst($product->type) }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 font-semibold text-gray-700">Category:</td>
                            <td class="py-2 text-gray-900">{{ $product->category ? $product->category->name : 'None' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 font-semibold text-gray-700">Subcategory:</td>
                            <td class="py-2 text-gray-900">{{ $product->subcategory ?? 'None' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 font-semibold text-gray-700">Badge:</td>
                            <td class="py-2">
                                @if($product->badge)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">{{ $product->badge }}</span>
                                @else
                                    <span class="text-gray-500">None</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Pricing (All Currencies)</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach(config('payment.currencies') as $code => $currency)
                                @php
                                    $pricing = $product->prices()->where('currency_code', $code)->first();
                                @endphp
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="text-xs text-gray-500 mb-1">{{ $code }}</div>
                                    <div class="font-semibold text-gray-900">
                                        {{ $currency['symbol'] }}{{ number_format($pricing->price ?? 0, 2) }}
                                    </div>
                                    @if($pricing && $pricing->list_price && $pricing->list_price > $pricing->price)
                                        <div class="text-xs text-gray-500 line-through">
                                            {{ $currency['symbol'] }}{{ number_format($pricing->list_price, 2) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <div class="text-gray-700">{!! $product->description !!}</div>
                </div>
                
                @if($product->features)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Features</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($product->features as $feature)
                                <li class="text-gray-700">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if($product->tags)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->tags as $tag)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-sm rounded">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($product->requirements)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Requirements</h3>
                        <p class="text-gray-700">{{ $product->requirements }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Asset Information -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Product Information</h2>
                
                <div class="space-y-3 text-sm">
                    <div><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $product->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $product->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $product->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $product->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                    
                    <!-- Change Status Form -->
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="border-t pt-3 mt-3">
                        @csrf
                        @method('PUT')
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Change Status:</label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded mb-2">
                            <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ $product->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $product->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $product->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium">Update Status</button>
                    </form>
                    
                    <div class="border-t pt-3"></div>
                    <div><strong>Featured:</strong> {{ $product->is_featured ? 'Yes' : 'No' }}</div>
                    <div><strong>Author:</strong> {{ $product->user ? $product->user->name : 'Admin Created' }}</div>
                    <div><strong>Created:</strong> {{ $product->created_at->format('M j, Y g:i A') }}</div>
                    <div><strong>Downloads:</strong> 
                        <button @click="showDownloadsModal = true" class="text-blue-600 hover:text-blue-800 hover:underline">
                            {{ $product->downloads }}
                        </button>
                    </div>
                    <div><strong>Purchases:</strong> 
                        <button @click="showPurchasesModal = true" class="text-blue-600 hover:text-blue-800 hover:underline">
                            {{ $purchases }}
                        </button>
                    </div>
                    <div><strong>Rating:</strong> {{ $product->rating ?? 'No rating' }}/5 ({{ $product->reviews_count ?? 0 }} reviews)</div>
                    @if($product->demo_url)
                        <div><strong>Demo:</strong> <a href="{{ $product->demo_url }}" target="_blank" class="text-blue-600 hover:underline">View Demo</a></div>
                    @endif
                </div>
                
                @if($product->file && count($product->file) > 0)
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="text-lg font-semibold mb-3">Attached Files</h3>
                        <div class="space-y-2">
                            @foreach($product->file as $index => $file)
                                @php
                                    $fullPath = storage_path('app/public/' . $file);
                                    $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
                                    $fileSizeMB = number_format($fileSize / 1048576, 2);
                                    $fileName = basename($file);
                                @endphp
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $fileName }}">{{ $fileName }}</p>
                                            <p class="text-xs text-gray-500">{{ $fileSizeMB }} MB</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($file) }}" download class="ml-3 flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    
    @include('modals.admin.downloads-modal')
    @include('modals.admin.purchases-modal')
</div>
</x-admin.app-layout>