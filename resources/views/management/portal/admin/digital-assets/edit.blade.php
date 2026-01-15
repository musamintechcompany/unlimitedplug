<x-admin.app-layout>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.digital-assets.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Digital Assets</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Digital Asset</h1>
    <p class="text-gray-600 mb-8">Edit {{ $digitalAsset->name }}</p>

    <form id="assetForm" action="{{ route('admin.digital-assets.update', $digitalAsset) }}" method="POST" enctype="multipart/form-data" x-data="uploadHandler()">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <label class="block text-sm font-medium mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $digitalAsset->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Type *</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Type</option>
                        <option value="website" {{ $digitalAsset->type == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="template" {{ $digitalAsset->type == 'template' ? 'selected' : '' }}>Template</option>
                        <option value="plugin" {{ $digitalAsset->type == 'plugin' ? 'selected' : '' }}>Plugin</option>
                        <option value="service" {{ $digitalAsset->type == 'service' ? 'selected' : '' }}>Service</option>
                        <option value="digital" {{ $digitalAsset->type == 'digital' ? 'selected' : '' }}>Digital Product</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Category *</label>
                    <select name="category_id" id="categorySelect" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                            <option value="{{ $category->id }}" {{ $digitalAsset->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Subcategory</label>
                <select name="subcategory_id" id="subcategorySelect" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">No Subcategory</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description *</label>
                <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description', $digitalAsset->description) }}</textarea>
            </div>

            @php
                $usdPricing = $digitalAsset->prices()->where('currency_code', 'USD')->first();
                $ngnPricing = $digitalAsset->prices()->where('currency_code', 'NGN')->first();
            @endphp

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">USD Price ($) *</label>
                    <input type="number" name="usd_price" value="{{ old('usd_price', $usdPricing->price ?? $digitalAsset->price) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">USD List Price ($)</label>
                    <input type="number" name="usd_list_price" value="{{ old('usd_list_price', $usdPricing->list_price ?? $digitalAsset->list_price) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">NGN Price (₦)</label>
                    <input type="number" name="ngn_price" value="{{ old('ngn_price', $ngnPricing->price ?? '') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">NGN List Price (₦)</label>
                    <input type="number" name="ngn_list_price" value="{{ old('ngn_list_price', $ngnPricing->list_price ?? '') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Demo URL</label>
                <input type="url" name="demo_url" value="{{ old('demo_url', $digitalAsset->demo_url) }}" placeholder="https://demo.example.com" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ $digitalAsset->is_featured ? 'checked' : '' }} class="rounded">
                    <span class="ml-2 text-sm font-medium">Featured Asset</span>
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Badge</label>
                <select name="badge" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">No Badge</option>
                    <option value="NEW" {{ $digitalAsset->badge == 'NEW' ? 'selected' : '' }}>NEW</option>
                    <option value="HOT" {{ $digitalAsset->badge == 'HOT' ? 'selected' : '' }}>HOT</option>
                    <option value="BESTSELLER" {{ $digitalAsset->badge == 'BESTSELLER' ? 'selected' : '' }}>BESTSELLER</option>
                    <option value="POPULAR" {{ $digitalAsset->badge == 'POPULAR' ? 'selected' : '' }}>POPULAR</option>
                    <option value="TRENDING" {{ $digitalAsset->badge == 'TRENDING' ? 'selected' : '' }}>TRENDING</option>
                    <option value="PREMIUM" {{ $digitalAsset->badge == 'PREMIUM' ? 'selected' : '' }}>PREMIUM</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Tags (comma separated)</label>
                <input type="text" name="tags" value="{{ old('tags', is_array($digitalAsset->tags) ? implode(', ', $digitalAsset->tags) : '') }}" placeholder="#responsive, #modern, #clean" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Features (one per line)</label>
                <textarea name="features" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('features', is_array($digitalAsset->features) ? implode("\n", $digitalAsset->features) : '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Requirements</label>
                <textarea name="requirements" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('requirements', $digitalAsset->requirements) }}</textarea>
            </div>

            <!-- File Uploads -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">File Uploads</h3>
                
                <div class="space-y-4">
                    @if($digitalAsset->banner)
                    <div>
                        <label class="block text-sm font-medium mb-2">Current Banner</label>
                        <img src="{{ Storage::url($digitalAsset->banner) }}" class="w-48 h-32 object-cover rounded border mb-2">
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-2">Upload New Banner</label>
                        <input type="file" name="banner" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    @if($digitalAsset->media && count($digitalAsset->media) > 0)
                    <div>
                        <label class="block text-sm font-medium mb-2">Current Media ({{ count($digitalAsset->media) }} files)</label>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-2">Add New Media Files</label>
                        <input type="file" name="media[]" accept="image/*,video/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    @if($digitalAsset->file && count($digitalAsset->file) > 0)
                    <div>
                        <label class="block text-sm font-medium mb-2">Current Asset Files ({{ count($digitalAsset->file) }} files)</label>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-2">Add New Asset Files</label>
                        <input type="file" name="file[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Upload Progress -->
                <div x-show="uploading" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-blue-900">Uploading files...</span>
                        <span class="text-sm font-bold text-blue-900" x-text="progress + '%'"></span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                    </div>
                    <p class="text-xs text-blue-700 mt-2" x-text="currentFile"></p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.digital-assets.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Cancel</a>
                <button type="submit" :disabled="uploading" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                    <span x-show="!uploading">Update Asset</span>
                    <span x-show="uploading">Uploading...</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('categorySelect').addEventListener('change', function() {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('subcategorySelect');
    subcategorySelect.innerHTML = '<option value="">Loading...</option>';
    
    if (categoryId) {
        fetch(`/api/categories/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="">No Subcategory</option>';
                data.forEach(sub => {
                    subcategorySelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                });
            });
    }
});

function uploadHandler() {
    return {
        uploading: false,
        progress: 0,
        currentFile: '',
        init() {
            const uploadId = Math.random().toString(36).substring(7);
            
            window.Echo.channel(`upload.${uploadId}`)
                .listen('.upload.progress', (e) => {
                    this.progress = e.progress;
                    this.currentFile = e.fileName || 'Processing...';
                });

            document.getElementById('assetForm').addEventListener('submit', (e) => {
                e.preventDefault();
                this.uploading = true;
                this.progress = 0;
                
                const formData = new FormData(e.target);
                const xhr = new XMLHttpRequest();
                
                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        this.progress = Math.round((event.loaded / event.total) * 100);
                    }
                });
                
                xhr.addEventListener('load', () => {
                    if (xhr.status === 302 || xhr.status === 200) {
                        window.location.href = '{{ route("admin.digital-assets.index") }}';
                    } else {
                        this.uploading = false;
                        alert('Upload failed');
                    }
                });
                
                xhr.open('POST', e.target.action);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.setRequestHeader('X-Upload-ID', uploadId);
                xhr.send(formData);
            });
        }
    };
}
</script>
@endpush
</x-admin.app-layout>
