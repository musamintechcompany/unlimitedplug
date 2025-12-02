<x-admin.app-layout>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.digital-assets.index') }}" 
           class="text-blue-600 hover:text-blue-800">&larr; Back to Digital Assets</a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create Digital Asset</h1>
        <p class="text-gray-600 mt-2">Create a new digital asset as admin</p>
    </div>

    <form action="{{ route('admin.digital-assets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Type</label>
                            <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="website" {{ old('type') == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="template" {{ old('type') == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="plugin" {{ old('type') == 'plugin' ? 'selected' : '' }}>Plugin</option>
                                <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="digital" {{ old('type') == 'digital' ? 'selected' : '' }}>Digital Product</option>
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
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                            @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Demo URL (Optional)</label>
                            <input type="url" name="demo_url" value="{{ old('demo_url') }}" 
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
                                @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Subcategory (Optional)</label>
                            <select name="subcategory_id" id="subcategorySelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Subcategory</option>
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
                    
                    <!-- USD Pricing -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium mb-3 text-blue-600">USD Pricing (Required)</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">USD Price ($) *</label>
                                <input type="number" name="usd_price" value="{{ old('usd_price') }}" step="0.01" min="0" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('usd_price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">USD List Price ($)</label>
                                <input type="number" name="usd_list_price" value="{{ old('usd_list_price') }}" step="0.01" min="0" 
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
                                <input type="number" name="ngn_price" value="{{ old('ngn_price') }}" step="0.01" min="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ngn_price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                <p class="text-xs text-gray-500 mt-1">If empty, will auto-convert from USD</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">NGN List Price (₦)</label>
                                <input type="number" name="ngn_list_price" value="{{ old('ngn_list_price') }}" step="0.01" min="0" 
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
                    <div>
                        <label class="block text-sm font-medium mb-2">Upload Banner</label>
                        <input type="file" name="banner" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('banner')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Media Files -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Media Files</h3>
                    <div>
                        <label class="block text-sm font-medium mb-2">Media Files (Images/Videos)</label>
                        <input type="file" name="media[]" accept="image/*,video/*" multiple 
                               id="mediaInput" onchange="previewMedia(this)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('media')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        <div id="mediaPreview" class="mt-4 grid grid-cols-2 gap-4"></div>
                    </div>
                </div>

                <!-- Asset Files -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Asset Files</h3>
                    <div>
                        <label class="block text-sm font-medium mb-2">Digital Asset Files (ZIP, etc.)</label>
                        <input type="file" name="file[]" multiple 
                               id="fileInput" onchange="previewFiles(this)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('file')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        <div id="filePreview" class="mt-4"></div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Custom Badge</label>
                            <select name="badge" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Badge</option>
                                <option value="NEW" {{ old('badge') === 'NEW' ? 'selected' : '' }}>NEW</option>
                                <option value="HOT" {{ old('badge') === 'HOT' ? 'selected' : '' }}>HOT</option>
                                <option value="BESTSELLER" {{ old('badge') === 'BESTSELLER' ? 'selected' : '' }}>BESTSELLER</option>
                                <option value="POPULAR" {{ old('badge') === 'POPULAR' ? 'selected' : '' }}>POPULAR</option>
                                <option value="TRENDING" {{ old('badge') === 'TRENDING' ? 'selected' : '' }}>TRENDING</option>
                                <option value="PREMIUM" {{ old('badge') === 'PREMIUM' ? 'selected' : '' }}>PREMIUM</option>
                                <option value="EXCLUSIVE" {{ old('badge') === 'EXCLUSIVE' ? 'selected' : '' }}>EXCLUSIVE</option>
                                <option value="LIMITED" {{ old('badge') === 'LIMITED' ? 'selected' : '' }}>LIMITED</option>
                                <option value="FEATURED" {{ old('badge') === 'FEATURED' ? 'selected' : '' }}>FEATURED</option>
                                <option value="TOP RATED" {{ old('badge') === 'TOP RATED' ? 'selected' : '' }}>TOP RATED</option>
                                <option value="EDITOR'S CHOICE" {{ old('badge') === "EDITOR'S CHOICE" ? 'selected' : '' }}>EDITOR'S CHOICE</option>
                                <option value="UPDATED" {{ old('badge') === 'UPDATED' ? 'selected' : '' }}>UPDATED</option>
                                <option value="FREE" {{ old('badge') === 'FREE' ? 'selected' : '' }}>FREE</option>
                            </select>
                            @error('badge')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            <p class="text-xs text-gray-500 mt-1">Badge appears in top-right of product card</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Tags (hashtag format, comma separated)</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" 
                                   placeholder="#responsive, #modern, #clean, #bootstrap"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tags')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Features (one per line)</label>
                            <textarea name="features" rows="4" 
                                      placeholder="Responsive design&#10;Cross-browser compatible&#10;SEO optimized"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('features') }}</textarea>
                            @error('features')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Requirements (Optional)</label>
                            <textarea name="requirements" rows="3" 
                                      placeholder="PHP 8.0+, Laravel 10+, MySQL 5.7+"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('requirements') }}</textarea>
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
                    Create Asset
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
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Subcategories loaded:', data);
                subcategorySelect.innerHTML = '<option value="">No Subcategory</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(subcategory => {
                        subcategorySelect.innerHTML += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
                subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
            });
    } else {
        subcategorySelect.innerHTML = '<option value="">No Subcategory</option>';
    }
});

function previewMedia(input) {
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                
                if (file.type.startsWith('image/')) {
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded border">
                    `;
                } else if (file.type.startsWith('video/')) {
                    div.innerHTML = `
                        <video class="w-full h-24 object-cover rounded border" controls>
                            <source src="${e.target.result}" type="${file.type}">
                        </video>
                    `;
                }
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function previewFiles(input) {
    const preview = document.getElementById('filePreview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const div = document.createElement('div');
            div.className = 'flex items-center p-2 bg-gray-50 rounded border mb-2';
            div.innerHTML = `
                <svg class="w-6 h-6 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                </svg>
                <span class="text-sm text-gray-700">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
            `;
            preview.appendChild(div);
        });
    }
}
</script>
</x-admin.app-layout>