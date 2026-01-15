<x-admin.app-layout>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.digital-assets.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Digital Assets</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Digital Asset</h1>
    <p class="text-gray-600 mb-8">Create a new digital asset as admin</p>

    <form id="assetForm" action="{{ route('admin.digital-assets.store') }}" method="POST" enctype="multipart/form-data" x-data="uploadHandler()">
        @csrf
        
        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <label class="block text-sm font-medium mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Type *</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Type</option>
                        <option value="website">Website</option>
                        <option value="template">Template</option>
                        <option value="plugin">Plugin</option>
                        <option value="service">Service</option>
                        <option value="digital">Digital Product</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Category *</label>
                    <select name="category_id" id="categorySelect" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">USD Price ($) *</label>
                    <input type="number" name="usd_price" value="{{ old('usd_price') }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">USD List Price ($)</label>
                    <input type="number" name="usd_list_price" value="{{ old('usd_list_price') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">NGN Price (₦)</label>
                    <input type="number" name="ngn_price" value="{{ old('ngn_price') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">NGN List Price (₦)</label>
                    <input type="number" name="ngn_list_price" value="{{ old('ngn_list_price') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Demo URL</label>
                <input type="url" name="demo_url" value="{{ old('demo_url') }}" placeholder="https://demo.example.com" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Badge</label>
                <select name="badge" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">No Badge</option>
                    <option value="NEW">NEW</option>
                    <option value="HOT">HOT</option>
                    <option value="BESTSELLER">BESTSELLER</option>
                    <option value="POPULAR">POPULAR</option>
                    <option value="TRENDING">TRENDING</option>
                    <option value="PREMIUM">PREMIUM</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Tags (comma separated)</label>
                <input type="text" name="tags" value="{{ old('tags') }}" placeholder="#responsive, #modern, #clean" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Features (one per line)</label>
                <textarea name="features" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('features') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Requirements</label>
                <textarea name="requirements" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('requirements') }}</textarea>
            </div>

            <!-- File Uploads -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">File Uploads</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Banner Image</label>
                        <input type="file" name="banner" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Media Files (Images/Videos)</label>
                        <input type="file" name="media[]" accept="image/*,video/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Asset Files (ZIP, etc.)</label>
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
                    <span x-show="!uploading">Create Asset</span>
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
