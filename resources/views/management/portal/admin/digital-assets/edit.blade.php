<x-admin.app-layout>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.digital-assets.index') }}" 
           class="text-blue-600 hover:text-blue-800">&larr; Back to Digital Assets</a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Digital Asset</h1>
        <p class="text-gray-600 mt-2">Edit {{ $digitalAsset->name }}</p>
    </div>

    <form action="{{ route('admin.digital-assets.update', $digitalAsset) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name', $digitalAsset->name) }}" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Type</label>
                            <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="website" {{ old('type', $digitalAsset->type) == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="template" {{ old('type', $digitalAsset->type) == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="plugin" {{ old('type', $digitalAsset->type) == 'plugin' ? 'selected' : '' }}>Plugin</option>
                                <option value="service" {{ old('type', $digitalAsset->type) == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="digital" {{ old('type', $digitalAsset->type) == 'digital' ? 'selected' : '' }}>Digital Product</option>
                            </select>
                            @error('type')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Description & Demo -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Description & Demo</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea name="description" rows="4" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $digitalAsset->description) }}</textarea>
                            @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Demo URL (Optional)</label>
                            <input type="url" name="demo_url" value="{{ old('demo_url', $digitalAsset->demo_url) }}" 
                                   placeholder="https://demo.example.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('demo_url')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>


            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Category Selection -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Category Selection</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Category</label>
                            <select name="category_id" id="categorySelect" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @php
                                    $currentSubcategory = \App\Models\Subcategory::where('name', $digitalAsset->subcategory)->first();
                                    $currentCategoryId = $digitalAsset->category_id ?? ($currentSubcategory ? $currentSubcategory->category_id : null);
                                @endphp
                                @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}" {{ $currentCategoryId == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Subcategory (Optional)</label>
                            <select name="subcategory_id" id="subcategorySelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Subcategory</option>
                                @if($currentSubcategory)
                                    @foreach($currentSubcategory->category->subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ $subcategory->id == $currentSubcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('subcategory_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-yellow-800 mb-2">Don't see your category?</h4>
                        <p class="text-sm text-yellow-700 mb-2">If the category you need doesn't exist, contact the admin to add it to the system.</p>
                        <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-600 hover:underline">Manage Categories →</a>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Pricing</h3>
                    
                    @php
                        $usdPricing = $digitalAsset->prices()->where('currency_code', 'USD')->first();
                        $ngnPricing = $digitalAsset->prices()->where('currency_code', 'NGN')->first();
                    @endphp
                    
                    <!-- USD Pricing -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium mb-3 text-blue-600">USD Pricing (Required)</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">USD Price ($) *</label>
                                <input type="number" name="usd_price" value="{{ old('usd_price', $usdPricing->price ?? $digitalAsset->price) }}" step="0.01" min="0" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('usd_price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">USD List Price ($)</label>
                                <input type="number" name="usd_list_price" value="{{ old('usd_list_price', $usdPricing->list_price ?? $digitalAsset->list_price) }}" step="0.01" min="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('usd_list_price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- NGN Pricing -->
                    <div>
                        <h4 class="text-md font-medium mb-3 text-green-600">NGN Pricing (Optional)</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">NGN Price (₦)</label>
                                <input type="number" name="ngn_price" value="{{ old('ngn_price', $ngnPricing->price ?? '') }}" step="0.01" min="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ngn_price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                <p class="text-xs text-gray-500 mt-1">If empty, will auto-convert from USD</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">NGN List Price (₦)</label>
                                <input type="number" name="ngn_list_price" value="{{ old('ngn_list_price', $ngnPricing->list_price ?? '') }}" step="0.01" min="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ngn_list_price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                <p class="text-xs text-gray-500 mt-1">If empty, will auto-convert from USD</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Image -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Banner Image</h3>
                    
                    @if($digitalAsset->banner)
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Current Banner</label>
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($digitalAsset->banner) }}" class="w-full h-32 object-cover rounded border">
                                <button type="button" onclick="deleteBanner('{{ $digitalAsset->id }}')"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-2">Upload New Banner</label>
                        <input type="file" name="banner" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('banner')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Media Files -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Media Files</h3>
                    
                    @if($digitalAsset->media && count($digitalAsset->media) > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Current Media Files</label>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($digitalAsset->media as $index => $media)
                                    <div class="relative">
                                        <img src="{{ Storage::url($media) }}" class="w-full h-24 object-cover rounded border">
                                        <button type="button" onclick="deleteMedia('{{ $digitalAsset->id }}', {{ $index }})"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-2">Add New Media Files (Images/Videos)</label>
                        <input type="file" name="media[]" accept="image/*,video/*" multiple 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('media')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Asset Files -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Asset Files</h3>
                    
                    @if($digitalAsset->file && count($digitalAsset->file) > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Current Asset Files</label>
                            <div class="space-y-2">
                                @foreach($digitalAsset->file as $index => $file)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded border">
                                        <span class="text-sm text-gray-700">{{ basename($file) }}</span>
                                        <button type="button" onclick="deleteFile('{{ $digitalAsset->id }}', {{ $index }})"
                                                class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-2">Add New Asset Files (ZIP, etc.)</label>
                        <input type="file" name="file[]" multiple 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('file')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" 
                                       {{ old('is_featured', $digitalAsset->is_featured) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm font-medium">Featured Asset</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Featured assets appear first in marketplace</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Custom Badge</label>
                            <select name="badge" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Badge</option>
                                <option value="NEW" {{ old('badge', $digitalAsset->badge) === 'NEW' ? 'selected' : '' }}>NEW</option>
                                <option value="HOT" {{ old('badge', $digitalAsset->badge) === 'HOT' ? 'selected' : '' }}>HOT</option>
                                <option value="BESTSELLER" {{ old('badge', $digitalAsset->badge) === 'BESTSELLER' ? 'selected' : '' }}>BESTSELLER</option>
                                <option value="POPULAR" {{ old('badge', $digitalAsset->badge) === 'POPULAR' ? 'selected' : '' }}>POPULAR</option>
                                <option value="TRENDING" {{ old('badge', $digitalAsset->badge) === 'TRENDING' ? 'selected' : '' }}>TRENDING</option>
                                <option value="PREMIUM" {{ old('badge', $digitalAsset->badge) === 'PREMIUM' ? 'selected' : '' }}>PREMIUM</option>
                                <option value="EXCLUSIVE" {{ old('badge', $digitalAsset->badge) === 'EXCLUSIVE' ? 'selected' : '' }}>EXCLUSIVE</option>
                                <option value="LIMITED" {{ old('badge', $digitalAsset->badge) === 'LIMITED' ? 'selected' : '' }}>LIMITED</option>
                                <option value="FEATURED" {{ old('badge', $digitalAsset->badge) === 'FEATURED' ? 'selected' : '' }}>FEATURED</option>
                                <option value="TOP RATED" {{ old('badge', $digitalAsset->badge) === 'TOP RATED' ? 'selected' : '' }}>TOP RATED</option>
                                <option value="EDITOR'S CHOICE" {{ old('badge', $digitalAsset->badge) === "EDITOR'S CHOICE" ? 'selected' : '' }}>EDITOR'S CHOICE</option>
                                <option value="UPDATED" {{ old('badge', $digitalAsset->badge) === 'UPDATED' ? 'selected' : '' }}>UPDATED</option>
                                <option value="FREE" {{ old('badge', $digitalAsset->badge) === 'FREE' ? 'selected' : '' }}>FREE</option>
                            </select>
                            @error('badge')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            <p class="text-xs text-gray-500 mt-1">Custom badge overrides featured and sale badges</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Tags (hashtag format, comma separated)</label>
                            <input type="text" name="tags" value="{{ old('tags', is_array($digitalAsset->tags) ? implode(', ', $digitalAsset->tags) : '') }}" 
                                   placeholder="#responsive, #modern, #clean, #bootstrap"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tags')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Features (one per line)</label>
                            <textarea name="features" rows="4" 
                                      placeholder="Responsive design&#10;Cross-browser compatible&#10;SEO optimized"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('features', is_array($digitalAsset->features) ? implode("\n", $digitalAsset->features) : '') }}</textarea>
                            @error('features')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Requirements (Optional)</label>
                            <textarea name="requirements" rows="3" 
                                      placeholder="PHP 8.0+, Laravel 10+, MySQL 5.7+"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('requirements', $digitalAsset->requirements) }}</textarea>
                            @error('requirements')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.digital-assets.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Asset
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Load subcategories when category changes
document.getElementById('categorySelect').addEventListener('change', function() {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('subcategorySelect');
    
    subcategorySelect.innerHTML = '<option value="">Loading...</option>';
    
    if (categoryId) {
        fetch(`/api/categories/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="">No Subcategory</option>';
                data.forEach(subcategory => {
                    subcategorySelect.innerHTML += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                });
            })
            .catch(() => {
                subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
            });
    } else {
        subcategorySelect.innerHTML = '<option value="">No Subcategory</option>';
    }
});

// Delete functions
function deleteBanner(assetId) {
    if (confirm('Are you sure you want to delete this banner?')) {
        fetch(`/management/portal/admin/digital-assets/${assetId}/delete-banner`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting banner');
            }
        });
    }
}

function deleteMedia(assetId, index) {
    if (confirm('Are you sure you want to delete this media file?')) {
        fetch(`/management/portal/admin/digital-assets/${assetId}/delete-media/${index}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting media file');
            }
        });
    }
}

function deleteFile(assetId, index) {
    if (confirm('Are you sure you want to delete this asset file?')) {
        fetch(`/management/portal/admin/digital-assets/${assetId}/delete-file/${index}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting asset file');
            }
        });
    }
}
</script>
</x-admin.app-layout>