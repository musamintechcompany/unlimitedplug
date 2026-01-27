<x-admin.app-layout>
<!-- Success Toast -->
@if(session('success'))
<div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
    {{ session('success') }}
</div>
<script>
    setTimeout(() => document.getElementById('toast')?.remove(), 3000);
</script>
@endif

<div class="w-full max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 py-4 sm:py-8">
    <div class="mb-4 sm:mb-6 px-4 sm:px-0">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Products</a>
    </div>

    <form id="assetForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" x-data="{ uploading: false, progress: 0, currentFile: '', showDemoUrl: false, showBadge: false, showFeatures: false, showRequirements: false, mediaFiles: [], productFiles: [], tags: [], tagInput: '', features: [''], requirements: [''] }">
        @csrf
        <input type="hidden" name="type" value="{{ request('type', 'digital') }}">
        
        <!-- Product Details Card -->
        <div class="bg-white border border-gray-200 rounded-none sm:rounded-lg p-4 sm:p-8 mb-4 sm:mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Product details</h2>
            <p class="text-sm text-gray-500 mb-6">Basic product details.</p>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Banner Image <span class="text-red-600">*</span></label>
            <div id="bannerDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-100 text-gray-600 text-sm mb-6 hover:bg-gray-200 cursor-pointer">
                <div id="bannerContent">
                    <div id="bannerUploadText" class="text-left break-words flex items-center gap-2">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span id="bannerText" class="break-words">Drag & Drop your banner image or <strong>Browse</strong></span>
                    </div>
                    <div id="bannerPreview" class="hidden mt-3 flex items-center gap-3">
                        <img id="bannerImage" class="w-10 h-10 object-cover rounded flex-shrink-0" />
                        <span id="bannerFileName" class="text-sm flex-1 break-all"></span>
                        <button type="button" id="bannerRemove" class="text-red-600 hover:text-red-800 text-xl font-bold flex-shrink-0">×</button>
                    </div>
                    <div id="bannerProgress" class="hidden mt-3">
                        <div class="w-full bg-gray-300 rounded-full h-2">
                            <div id="bannerProgressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Uploading...</p>
                    </div>
                </div>
                <input type="file" name="banner" id="bannerInput" accept="image/*" required class="hidden">
            </div>
            <script>
            (function() {
                const dropZone = document.getElementById('bannerDropZone');
                const fileInput = document.getElementById('bannerInput');
                const uploadText = document.getElementById('bannerUploadText');
                const preview = document.getElementById('bannerPreview');
                const previewImg = document.getElementById('bannerImage');
                const fileName = document.getElementById('bannerFileName');
                const removeBtn = document.getElementById('bannerRemove');
                const progress = document.getElementById('bannerProgress');
                const progressBar = document.getElementById('bannerProgressBar');

                dropZone.addEventListener('click', (e) => {
                    if (e.target !== removeBtn) fileInput.click();
                });

                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.classList.add('bg-gray-300');
                });

                dropZone.addEventListener('dragleave', () => {
                    dropZone.classList.remove('bg-gray-300');
                });

                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('bg-gray-300');
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        handleFileSelect(e.dataTransfer.files[0]);
                    }
                });

                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length) {
                        handleFileSelect(fileInput.files[0]);
                    }
                });

                removeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fileInput.value = '';
                    preview.classList.add('hidden');
                    uploadText.classList.remove('hidden');
                });

                function handleFileSelect(file) {
                    // Validate banner image size (5MB max)
                    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if (file.size > maxSize) {
                        alert('Banner image must be 5MB or smaller. Please choose a smaller image.');
                        fileInput.value = '';
                        return;
                    }
                    
                    uploadText.classList.add('hidden');
                    progress.classList.remove('hidden');
                    
                    let width = 0;
                    const interval = setInterval(() => {
                        width += 10;
                        progressBar.style.width = width + '%';
                        if (width >= 100) {
                            clearInterval(interval);
                            progress.classList.add('hidden');
                            showPreview(file);
                        }
                    }, 50);
                }

                function showPreview(file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        fileName.textContent = file.name;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            })();
            </script>

            <label class="block text-sm font-semibold text-gray-900 mb-2 mt-6">Product Media (Images/Videos)</label>
            <div id="mediaDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-100 text-gray-600 text-sm mb-6 hover:bg-gray-200 cursor-pointer">
                <div id="mediaUploadText" class="text-left break-words flex items-center gap-2">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                    </svg>
                    <span class="break-words">Drag & Drop media files or <strong>Browse</strong></span>
                </div>
                <div id="mediaProgress" class="hidden mt-3">
                    <div class="w-full bg-gray-300 rounded-full h-2">
                        <div id="mediaProgressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Uploading <span id="mediaProgressText">0</span>%...</p>
                </div>
                <div id="mediaFilesList" class="hidden mt-3 space-y-2"></div>
                <input type="file" name="media[]" id="mediaInput" accept="image/*,video/*" multiple class="hidden">
            </div>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Name of product <span class="text-red-600">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name of product *" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg mb-5">

            <label class="block text-sm font-semibold text-gray-900 mb-2">Sale Price (All currency prices required) <span class="text-red-600">*</span></label>
            <div x-data="{ showListPrice: false }">
                <div class="overflow-x-auto mb-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        .overflow-x-auto::-webkit-scrollbar { display: none; }
                    </style>
                    <div class="inline-flex flex-col gap-2 min-w-full">
                        <!-- Sale Price Row -->
                        <div class="flex gap-3">
                            @foreach(config('payment.currencies') as $code => $currency)
                                <div class="flex-shrink-0 w-32 relative">
                                    <label class="block text-xs font-medium mb-1">
                                        {{ $code }} <span class="text-red-600">*</span>
                                        <button type="button" class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full border border-gray-400 text-gray-600 hover:bg-gray-100 text-[10px] ml-0.5 relative group" onclick="event.preventDefault()">
                                            ?
                                            <div class="absolute left-full top-0 ml-2 w-48 bg-white border border-gray-300 shadow-lg text-gray-900 text-xs rounded py-2 px-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity pointer-events-none whitespace-normal" style="z-index: 9999;">
                                                <div class="font-semibold mb-1">{{ $currency['name'] }}</div>
                                                <div class="text-gray-600">Set to 0 for free product</div>
                                                <div class="absolute right-full top-2 border-4 border-transparent border-r-white"></div>
                                                <div class="absolute right-full top-2 border-4 border-transparent border-r-gray-300" style="margin-right: 1px;"></div>
                                            </div>
                                        </button>
                                    </label>
                                    <input type="number" name="{{ strtolower($code) }}_price" value="{{ old(strtolower($code) . '_price') }}" step="0.01" min="0" required class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded" placeholder="0.00">
                                </div>
                            @endforeach
                        </div>
                        <!-- List Price Row (Hidden by default) -->
                        <div x-show="showListPrice" class="flex gap-3">
                            @foreach(config('payment.currencies') as $code => $currency)
                                <div class="flex-shrink-0 w-32 relative">
                                    <label class="block text-xs font-medium mb-1">
                                        {{ $code }} List
                                        <button type="button" class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full border border-gray-400 text-gray-600 hover:bg-gray-100 text-[10px] ml-0.5 relative group" onclick="event.preventDefault()">
                                            ?
                                            <div class="absolute left-full top-0 ml-2 w-48 bg-white border border-gray-300 shadow-lg text-gray-900 text-xs rounded py-2 px-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity pointer-events-none whitespace-normal" style="z-index: 9999;">
                                                <div class="font-semibold mb-1">{{ $currency['name'] }}</div>
                                                <div class="text-gray-600">Original price (strikethrough)</div>
                                                <div class="absolute right-full top-2 border-4 border-transparent border-r-white"></div>
                                                <div class="absolute right-full top-2 border-4 border-transparent border-r-gray-300" style="margin-right: 1px;"></div>
                                            </div>
                                        </button>
                                    </label>
                                    <input type="number" name="{{ strtolower($code) }}_list_price" value="{{ old(strtolower($code) . '_list_price') }}" step="0.01" min="0" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded" placeholder="0.00">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mb-4">Set price to 0 for a free product.</p>

                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" x-model="showListPrice" class="rounded">
                    <span>Show original price (strikethrough)</span>
                </label>
            </div>

            <label class="block text-sm font-semibold text-gray-900 mb-2 mt-6">Description <span class="text-red-600">*</span></label>
            <div id="editor" style="height: 200px;"></div>
            <input type="hidden" name="description" id="description" required>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof Quill !== 'undefined') {
                        var quill = new Quill('#editor', {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{ 'header': [1, 2, 3, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ 'color': [] }, { 'background': [] }],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'align': [] }],
                                    ['link'],
                                    ['clean']
                                ]
                            }
                        });
                        document.getElementById('assetForm').onsubmit = function() {
                            document.getElementById('description').value = quill.root.innerHTML;
                        };
                    }
                });
            </script>

            <div class="text-xs font-bold text-gray-500 tracking-wide mb-2 mt-6">CATEGORIZE YOUR PRODUCT</div>
            <p class="text-sm text-gray-500 mb-4">Categorize your product with our predefined list of categories; this helps with SEO and will help people find your product easily.</p>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Category <span class="text-red-600">*</span></label>
            <select name="category_id" id="categorySelect" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg mb-5" onchange="loadSubcategories(this.value)">
                <option value="">Select category</option>
                @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Sub Category</label>
            <select name="subcategory_id" id="subcategorySelect" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg mb-5" style="max-width: 400px;">
                <option value="">Select sub category</option>
            </select>
            <script>
            function loadSubcategories(categoryId) {
                const subcategorySelect = document.getElementById('subcategorySelect');
                subcategorySelect.innerHTML = '<option value="">Loading...</option>';
                
                if (categoryId) {
                    fetch('/api/categories/' + categoryId + '/subcategories')
                        .then(response => response.json())
                        .then(data => {
                            subcategorySelect.innerHTML = '<option value="">Select sub category</option>';
                            data.forEach(sub => {
                                subcategorySelect.innerHTML += '<option value="' + sub.id + '">' + sub.name + '</option>';
                            });
                        });
                } else {
                    subcategorySelect.innerHTML = '<option value="">Select sub category</option>';
                }
            }
            </script>
        </div>

        <!-- Additional Details Card -->
        <div class="bg-white border border-gray-200 rounded-none sm:rounded-lg p-4 sm:p-8 mb-4 sm:mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Additional details</h2>
            <p class="text-sm text-gray-500 mb-6">Optional product information.</p>

            <!-- Tags -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-900 mb-2">Tags</label>
                <div class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg min-h-[48px] flex flex-wrap gap-2 items-center" @click="$refs.tagInput.focus()">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
                            <span x-text="tag"></span>
                            <button type="button" @click="tags.splice(index, 1)" class="text-gray-500 hover:text-gray-700 font-bold">&times;</button>
                        </span>
                    </template>
                    <input 
                        type="text" 
                        x-ref="tagInput"
                        x-model="tagInput" 
                        @keydown.comma.prevent="if(tagInput.trim()) { tags.push(tagInput.trim()); tagInput = ''; }"
                        @keydown.enter.prevent="if(tagInput.trim()) { tags.push(tagInput.trim()); tagInput = ''; }"
                        placeholder="Type and press comma or enter"
                        class="flex-1 min-w-[200px] outline-none border-0 p-0 focus:ring-0"
                    >
                </div>
                <input type="hidden" name="tags" :value="tags.join(',')">
            </div>

            <!-- Preview URL Toggle -->
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-900">Add preview URL</label>
                    <button type="button" @click="showDemoUrl = !showDemoUrl" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="showDemoUrl ? 'bg-blue-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="showDemoUrl ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
                <div x-show="showDemoUrl" x-transition class="mt-3">
                    <input type="url" name="demo_url" value="{{ old('demo_url') }}" placeholder="https://demo.example.com" :required="showDemoUrl" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- Badge Toggle -->
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-900">Add badge</label>
                    <button type="button" @click="showBadge = !showBadge" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="showBadge ? 'bg-blue-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="showBadge ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
                <div x-show="showBadge" x-transition class="mt-3">
                    <select name="badge" :required="showBadge" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg">
                        <option value="">Select Badge</option>
                        @foreach(config('product.badges') as $badge)
                            <option value="{{ $badge }}">{{ $badge }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Features Toggle -->
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-900">Add features</label>
                    <button type="button" @click="showFeatures = !showFeatures" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="showFeatures ? 'bg-blue-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="showFeatures ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
                <div x-show="showFeatures" x-transition class="mt-3 space-y-3">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="flex gap-2">
                            <input type="text" :name="'features[]'" x-model="features[index]" placeholder="Enter feature" :required="showFeatures" class="flex-1 px-4 py-3 text-sm border border-gray-300 rounded-lg">
                            <button type="button" @click="features.splice(index, 1)" x-show="features.length > 1" class="px-3 text-red-600 hover:text-red-800 font-bold text-xl">&times;</button>
                        </div>
                    </template>
                    <button type="button" @click="features.push('')" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add Field</button>
                </div>
            </div>

            <!-- Requirements Toggle -->
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-900">Add requirements</label>
                    <button type="button" @click="showRequirements = !showRequirements" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="showRequirements ? 'bg-blue-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="showRequirements ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
                <div x-show="showRequirements" x-transition class="mt-3 space-y-3">
                    <template x-for="(requirement, index) in requirements" :key="index">
                        <div class="flex gap-2">
                            <input type="text" :name="'requirements[]'" x-model="requirements[index]" placeholder="Enter requirement" :required="showRequirements" class="flex-1 px-4 py-3 text-sm border border-gray-300 rounded-lg">
                            <button type="button" @click="requirements.splice(index, 1)" x-show="requirements.length > 1" class="px-3 text-red-600 hover:text-red-800 font-bold text-xl">&times;</button>
                        </div>
                    </template>
                    <button type="button" @click="requirements.push('')" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add Field</button>
                </div>
            </div>

            <!-- License Type Toggle -->
            <div x-data="{ showLicense: false }">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-900">Add license type</label>
                    <button type="button" @click="showLicense = !showLicense" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="showLicense ? 'bg-blue-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="showLicense ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
                <div x-show="showLicense" x-transition class="mt-3">
                    <select name="license_type" :required="showLicense" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg">
                        <option value="">Select License Type</option>
                        @foreach(config('licenses') as $key => $license)
                            <option value="{{ $key }}">{{ $license['icon'] }} {{ $license['name'] }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Choose the license type for this digital product</p>
                </div>
            </div>
        </div>

        <!-- Admin Controls Card -->
        <div class="bg-white border border-gray-200 rounded-none sm:rounded-lg p-4 sm:p-8 mb-4 sm:mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Admin controls</h2>
            <p class="text-sm text-gray-500 mb-6">Manage product visibility and status.</p>

            <!-- Status Dropdown -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-900 mb-2">Product Status</label>
                <select name="status" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg">
                    <option value="draft" selected>Draft</option>
                    <option value="pending">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Set the approval status for this product</p>
            </div>

            <!-- Featured Toggle -->
            <div class="mb-5">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-semibold text-gray-900">Featured Product</label>
                        <p class="text-xs text-gray-500 mt-1">Show this product in featured section</p>
                    </div>
                    <label class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer" :class="$el.querySelector('input').checked ? 'bg-blue-600' : 'bg-gray-300'">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" onchange="this.parentElement.className = this.checked ? 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer bg-blue-600' : 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform peer-checked:translate-x-6 translate-x-1"></span>
                    </label>
                </div>
            </div>

            <!-- Active Toggle -->
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-semibold text-gray-900">Active Product</label>
                        <p class="text-xs text-gray-500 mt-1">Make product visible in marketplace</p>
                    </div>
                    <label class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer" :class="$el.querySelector('input').checked ? 'bg-blue-600' : 'bg-gray-300'">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer" onchange="this.parentElement.className = this.checked ? 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer bg-blue-600' : 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform peer-checked:translate-x-6 translate-x-1"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- File Uploads Card -->
        <div class="bg-white border border-gray-200 rounded-none sm:rounded-lg p-4 sm:p-8 mb-4 sm:mb-6" x-data="{ fileAccessType: 'downloadable' }">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">File uploads</h2>
            <p class="text-sm text-gray-500 mb-6">Upload product files.</p>

            <div class="text-xs font-bold text-gray-500 tracking-wide mb-2">FILE ACCESS TYPE</div>
            <div class="inline-flex border border-gray-300 rounded-lg overflow-hidden mb-4">
                <button type="button" @click="fileAccessType = 'downloadable'" :class="fileAccessType === 'downloadable' ? 'bg-gray-200 font-semibold' : 'bg-white'" class="px-5 py-2.5 text-sm border-r border-gray-300">Downloadable file</button>
                <button type="button" @click="fileAccessType = 'read_only'" :class="fileAccessType === 'read_only' ? 'bg-gray-200 font-semibold' : 'bg-white'" class="px-5 py-2.5 text-sm">Read online only (PDF)</button>
            </div>
            <input type="hidden" name="file_access_type" :value="fileAccessType">

            <div class="text-sm text-gray-700 mb-2"><strong>Maximum file size:</strong> <span x-text="fileAccessType === 'downloadable' ? '750MB' : '50MB'"></span></div>
            <p class="text-sm text-gray-500 mb-4" x-show="fileAccessType === 'downloadable'">To upload multiple files or a bundle, simply zip (compress) all the files to a .zip file. Ensure it's .zip and not .rar.</p>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Product Files</label>
            <div id="filesDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-100 text-gray-600 text-sm mb-6 hover:bg-gray-200 cursor-pointer">
                <div id="filesUploadText" class="text-left break-words flex items-center gap-2">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="break-words">Drag & Drop product files or <strong>Browse</strong></span>
                </div>
                <div id="filesProgress" class="hidden mt-3">
                    <div class="w-full bg-gray-300 rounded-full h-2">
                        <div id="filesProgressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Uploading <span id="filesProgressText">0</span>%...</p>
                </div>
                <div id="productFilesList" class="hidden mt-3 space-y-2"></div>
                <input type="file" name="file[]" id="filesInput" multiple class="hidden">
            </div>
        </div>
        
        <script>
        (function() {
            // Media Upload Handler
            const mediaDropZone = document.getElementById('mediaDropZone');
            const mediaInput = document.getElementById('mediaInput');
            const mediaUploadText = document.getElementById('mediaUploadText');
            const mediaFilesList = document.getElementById('mediaFilesList');
            let mediaFilesArray = [];

            mediaDropZone.addEventListener('click', () => mediaInput.click());
            mediaDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                mediaDropZone.classList.add('bg-gray-300');
            });
            mediaDropZone.addEventListener('dragleave', () => mediaDropZone.classList.remove('bg-gray-300'));
            mediaDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                mediaDropZone.classList.remove('bg-gray-300');
                if (e.dataTransfer.files.length) handleMediaFiles(e.dataTransfer.files);
            });
            mediaInput.addEventListener('change', () => {
                if (mediaInput.files.length) handleMediaFiles(mediaInput.files);
            });

            function handleMediaFiles(files) {
                const maxImageSize = 5 * 1024 * 1024; // 5MB
                const maxVideoSize = 50 * 1024 * 1024; // 50MB
                const invalidFiles = [];
                
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/') && file.size > maxImageSize) {
                        invalidFiles.push(`${file.name} (${(file.size / 1024 / 1024).toFixed(1)}MB - exceeds 5MB limit)`);
                    } else if (file.type.startsWith('video/') && file.size > maxVideoSize) {
                        invalidFiles.push(`${file.name} (${(file.size / 1024 / 1024).toFixed(1)}MB - exceeds 50MB limit)`);
                    }
                });
                
                if (invalidFiles.length > 0) {
                    alert('The following files exceed size limits:\n\n' + invalidFiles.join('\n') + '\n\nPlease choose smaller files or remove these files.');
                    mediaInput.value = '';
                    return;
                }
                
                mediaFilesArray = Array.from(files);
                showMediaProgress();
            }

            function showMediaProgress() {
                const progress = document.getElementById('mediaProgress');
                const progressBar = document.getElementById('mediaProgressBar');
                const progressText = document.getElementById('mediaProgressText');
                
                mediaUploadText.classList.add('hidden');
                progress.classList.remove('hidden');
                
                let width = 0;
                const interval = setInterval(() => {
                    width += 10;
                    progressBar.style.width = width + '%';
                    progressText.textContent = width;
                    if (width >= 100) {
                        clearInterval(interval);
                        progress.classList.add('hidden');
                        displayMediaFiles();
                    }
                }, 50);
            }

            function displayMediaFiles() {
                if (mediaFilesArray.length === 0) {
                    mediaFilesList.classList.add('hidden');
                    mediaUploadText.classList.remove('hidden');
                    return;
                }
                mediaFilesList.classList.remove('hidden');
                mediaFilesList.innerHTML = '';
                
                mediaFilesArray.forEach((file, index) => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'flex items-center gap-3 p-2 bg-white rounded border border-gray-200';
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            fileDiv.innerHTML = `
                                <img src="${e.target.result}" class="w-10 h-10 object-cover rounded flex-shrink-0" />
                                <span class="text-sm flex-1 break-all">${file.name}</span>
                                <button type="button" onclick="removeMediaFile(${index})" class="text-red-600 hover:text-red-800 text-xl font-bold">×</button>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        fileDiv.innerHTML = `
                            <span class="text-xl">🎬</span>
                            <span class="text-sm flex-1 break-all">${file.name}</span>
                            <button type="button" onclick="removeMediaFile(${index})" class="text-red-600 hover:text-red-800 text-xl font-bold">×</button>
                        `;
                    }
                    
                    mediaFilesList.appendChild(fileDiv);
                });
            }

            window.removeMediaFile = function(index) {
                mediaFilesArray.splice(index, 1);
                const dt = new DataTransfer();
                mediaFilesArray.forEach(file => dt.items.add(file));
                mediaInput.files = dt.files;
                displayMediaFiles();
            };

            // Product Files Upload Handler
            const filesDropZone = document.getElementById('filesDropZone');
            const filesInput = document.getElementById('filesInput');
            const filesUploadText = document.getElementById('filesUploadText');
            const productFilesList = document.getElementById('productFilesList');
            let productFilesArray = [];

            filesDropZone.addEventListener('click', () => filesInput.click());
            filesDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                filesDropZone.classList.add('bg-gray-300');
            });
            filesDropZone.addEventListener('dragleave', () => filesDropZone.classList.remove('bg-gray-300'));
            filesDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                filesDropZone.classList.remove('bg-gray-300');
                if (e.dataTransfer.files.length) handleProductFiles(e.dataTransfer.files);
            });
            filesInput.addEventListener('change', () => {
                if (filesInput.files.length) handleProductFiles(filesInput.files);
            });

            function handleProductFiles(files) {
                const maxFileSize = 750 * 1024 * 1024; // 750MB
                const invalidFiles = [];
                
                Array.from(files).forEach(file => {
                    if (file.size > maxFileSize) {
                        invalidFiles.push(`${file.name} (${(file.size / 1024 / 1024).toFixed(1)}MB - exceeds 750MB limit)`);
                    }
                });
                
                if (invalidFiles.length > 0) {
                    alert('The following files exceed 750MB limit:\n\n' + invalidFiles.join('\n') + '\n\nPlease choose smaller files.');
                    filesInput.value = '';
                    return;
                }
                
                productFilesArray = Array.from(files);
                showFilesProgress();
            }

            function showFilesProgress() {
                const progress = document.getElementById('filesProgress');
                const progressBar = document.getElementById('filesProgressBar');
                const progressText = document.getElementById('filesProgressText');
                
                filesUploadText.classList.add('hidden');
                progress.classList.remove('hidden');
                
                let width = 0;
                const interval = setInterval(() => {
                    width += 10;
                    progressBar.style.width = width + '%';
                    progressText.textContent = width;
                    if (width >= 100) {
                        clearInterval(interval);
                        progress.classList.add('hidden');
                        displayProductFiles();
                    }
                }, 50);
            }

            function displayProductFiles() {
                if (productFilesArray.length === 0) {
                    productFilesList.classList.add('hidden');
                    filesUploadText.classList.remove('hidden');
                    return;
                }
                productFilesList.classList.remove('hidden');
                productFilesList.innerHTML = productFilesArray.map((file, index) => `
                    <div class="flex items-center gap-3 p-2 bg-white rounded border border-gray-200">
                        <span class="text-xl">📁</span>
                        <span class="text-sm flex-1 break-all">${file.name}</span>
                        <button type="button" onclick="removeProductFile(${index})" class="text-red-600 hover:text-red-800 text-xl font-bold">×</button>
                    </div>
                `).join('');
            }

            window.removeProductFile = function(index) {
                productFilesArray.splice(index, 1);
                const dt = new DataTransfer();
                productFilesArray.forEach(file => dt.items.add(file));
                filesInput.files = dt.files;
                displayProductFiles();
            };
        })();
        </script>

        <!-- Submit Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 px-4 sm:px-0">
            <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 text-sm font-medium bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-center">Cancel</a>
            <button type="submit" name="action" value="create_another" class="w-full sm:w-auto px-6 sm:px-8 py-3 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700">Create & Add New</button>
            <button type="submit" name="action" value="create" class="w-full sm:w-auto px-6 sm:px-8 py-3 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Product</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
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
                        window.location.href = '{{ route("admin.products.index") }}';
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
