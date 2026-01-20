<x-admin.app-layout>
<div class="w-full max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 py-4 sm:py-8">
    <div class="mb-4 sm:mb-6 px-4 sm:px-0">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Products</a>
    </div>

    <form id="assetForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="{ uploading: false, progress: 0, currentFile: '', showDemoUrl: {{ $product->demo_url ? 'true' : 'false' }}, showBadge: {{ $product->badge ? 'true' : 'false' }}, showFeatures: {{ $product->features ? 'true' : 'false' }}, showRequirements: {{ $product->requirements ? 'true' : 'false' }}, mediaFiles: [], productFiles: [], tags: {{ json_encode($product->tags ?? []) }}, tagInput: '', features: {{ json_encode($product->features ?? ['']) }}, requirements: {{ json_encode($product->requirements ? explode("\n", $product->requirements) : ['']) }} }">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="{{ $product->type }}">
        
        <!-- Product Details Card -->
        <div class="bg-white border border-gray-200 rounded-none sm:rounded-lg p-4 sm:p-8 mb-4 sm:mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Product details</h2>
            <p class="text-sm text-gray-500 mb-6">Basic product details.</p>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Banner Image <span class="text-red-600">*</span></label>
            <div id="bannerDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-100 text-gray-600 text-sm mb-6 hover:bg-gray-200 cursor-pointer">
                <div id="bannerContent">
                    @if($product->banner)
                    <div id="bannerPreview" class="mt-3 flex items-center gap-3">
                        <img id="bannerImage" src="{{ Storage::url($product->banner) }}" class="w-10 h-10 object-cover rounded flex-shrink-0" />
                        <span id="bannerFileName" class="text-sm flex-1 break-all">{{ basename($product->banner) }}</span>
                        <button type="button" id="bannerRemove" class="text-red-600 hover:text-red-800 text-xl font-bold flex-shrink-0">&times;</button>
                    </div>
                    <div id="bannerUploadText" class="hidden text-left break-words flex items-center gap-2">
                    @else
                    <div id="bannerUploadText" class="text-left break-words flex items-center gap-2">
                    @endif
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span id="bannerText" class="break-words">Drag & Drop your banner image or <strong>Browse</strong></span>
                    </div>
                    @if(!$product->banner)
                    <div id="bannerPreview" class="hidden mt-3 flex items-center gap-3">
                        <img id="bannerImage" class="w-10 h-10 object-cover rounded flex-shrink-0" />
                        <span id="bannerFileName" class="text-sm flex-1 break-all"></span>
                        <button type="button" id="bannerRemove" class="text-red-600 hover:text-red-800 text-xl font-bold flex-shrink-0">&times;</button>
                    </div>
                    @endif
                    <div id="bannerProgress" class="hidden mt-3">
                        <div class="w-full bg-gray-300 rounded-full h-2">
                            <div id="bannerProgressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Uploading...</p>
                    </div>
                </div>
                <input type="file" name="banner" id="bannerInput" accept="image/*" class="hidden">
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
                @if($product->media && count($product->media) > 0)
                <div id="mediaUploadText" class="hidden text-left break-words flex items-center gap-2">
                @else
                <div id="mediaUploadText" class="text-left break-words flex items-center gap-2">
                @endif
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
                @if($product->media && count($product->media) > 0)
                <div id="mediaFilesList" class="mt-3 space-y-2">
                    @foreach($product->media as $index => $media)
                    <div class="flex items-center gap-3 p-2 bg-white rounded border border-gray-200">
                        <img src="{{ Storage::url($media) }}" class="w-10 h-10 object-cover rounded">
                        <span class="text-sm flex-1 break-all">{{ basename($media) }}</span>
                        <span class="text-xs text-gray-500">Current</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div id="mediaFilesList" class="hidden mt-3 space-y-2"></div>
                @endif
                <input type="file" name="media[]" id="mediaInput" accept="image/*,video/*" multiple class="hidden">
            </div>
            <script>
            (function() {
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
                    mediaFilesList.innerHTML = mediaFilesArray.map((file, index) => `
                        <div class="flex items-center gap-3 p-2 bg-white rounded border border-gray-200">
                            <span class="text-xl">🖼️</span>
                            <span class="text-sm flex-1 break-all">${file.name}</span>
                            <button type="button" onclick="removeMediaFile(${index})" class="text-red-600 hover:text-red-800 text-xl font-bold">×</button>
                        </div>
                    `).join('');
                }

                window.removeMediaFile = function(index) {
                    mediaFilesArray.splice(index, 1);
                    const dt = new DataTransfer();
                    mediaFilesArray.forEach(file => dt.items.add(file));
                    mediaInput.files = dt.files;
                    displayMediaFiles();
                };
            })();
            </script>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Name of product <span class="text-red-600">*</span></label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Name of product *" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg mb-5">

            <label class="block text-sm font-semibold text-gray-900 mb-2">Sale Price (All currency prices required)</label>
            <div x-data="{ showListPrice: {{ $product->list_price ? 'true' : 'false' }} }">
                <div class="overflow-x-auto mb-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        .overflow-x-auto::-webkit-scrollbar { display: none; }
                    </style>
                    <div class="inline-flex flex-col gap-2 min-w-full">
                        <div class="flex gap-3">
                            @foreach(config('payment.currencies') as $code => $currency)
                                @php
                                    $pricing = $product->prices()->where('currency_code', $code)->first();
                                @endphp
                                <div class="flex-shrink-0 w-32">
                                    <label class="block text-xs font-medium mb-1">{{ $code }} *</label>
                                    <input type="number" name="{{ strtolower($code) }}_price" value="{{ old(strtolower($code) . '_price', $pricing->price ?? ($code === 'USD' ? $product->price : '')) }}" step="0.01" min="0" required class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded" placeholder="0.00">
                                </div>
                            @endforeach
                        </div>
                        <div x-show="showListPrice" class="flex gap-3">
                            @foreach(config('payment.currencies') as $code => $currency)
                                @php
                                    $pricing = $product->prices()->where('currency_code', $code)->first();
                                @endphp
                                <div class="flex-shrink-0 w-32">
                                    <label class="block text-xs font-medium mb-1">{{ $code }} List</label>
                                    <input type="number" name="{{ strtolower($code) }}_list_price" value="{{ old(strtolower($code) . '_list_price', $pricing->list_price ?? ($code === 'USD' ? $product->list_price : '')) }}" step="0.01" min="0" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded" placeholder="0.00">
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
            <div id="editor" style="height: 200px;">{!! old('description', $product->description) !!}</div>
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
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Sub Category</label>
            <select name="subcategory_id" id="subcategorySelect" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg mb-5" style="max-width: 400px;">
                <option value="">Select sub category</option>
            </select>
            <script>
            function loadSubcategories(categoryId, selectedSubcategoryId = null) {
                const subcategorySelect = document.getElementById('subcategorySelect');
                subcategorySelect.innerHTML = '<option value="">Loading...</option>';
                
                if (categoryId) {
                    fetch('/api/categories/' + categoryId + '/subcategories')
                        .then(response => response.json())
                        .then(data => {
                            subcategorySelect.innerHTML = '<option value="">Select sub category</option>';
                            data.forEach(sub => {
                                const selected = selectedSubcategoryId && sub.id == selectedSubcategoryId ? 'selected' : '';
                                subcategorySelect.innerHTML += '<option value="' + sub.id + '" ' + selected + '>' + sub.name + '</option>';
                            });
                        });
                } else {
                    subcategorySelect.innerHTML = '<option value="">Select sub category</option>';
                }
            }
            
            // Load subcategories on page load if category is selected
            document.addEventListener('DOMContentLoaded', function() {
                const categorySelect = document.getElementById('categorySelect');
                const selectedCategory = categorySelect.value;
                const selectedSubcategory = {{ $product->subcategory_id ?? 'null' }};
                
                if (selectedCategory) {
                    loadSubcategories(selectedCategory, selectedSubcategory);
                }
            });
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
                    <input type="url" name="demo_url" value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://demo.example.com" :required="showDemoUrl" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg">
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
                            <option value="{{ $badge }}" {{ $product->badge == $badge ? 'selected' : '' }}>{{ $badge }}</option>
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
            <div>
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
        </div>

        <!-- File Uploads Card -->
        <div class="bg-white border border-gray-200 rounded-none sm:rounded-lg p-4 sm:p-8 mb-4 sm:mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">File uploads</h2>
            <p class="text-sm text-gray-500 mb-6">Upload product files.</p>

            <label class="block text-sm font-semibold text-gray-900 mb-2">Product Files</label>
            <div id="filesDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-100 text-gray-600 text-sm mb-6 hover:bg-gray-200 cursor-pointer">
                @if($product->file && count($product->file) > 0)
                <div id="filesUploadText" class="hidden text-left break-words flex items-center gap-2">
                @else
                <div id="filesUploadText" class="text-left break-words flex items-center gap-2">
                @endif
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
                @if($product->file && count($product->file) > 0)
                <div id="productFilesList" class="mt-3 space-y-2">
                    @foreach($product->file as $index => $file)
                    <div class="flex items-center gap-3 p-2 bg-white rounded border border-gray-200">
                        <span class="text-xl">📁</span>
                        <span class="text-sm flex-1 break-all">{{ basename($file) }}</span>
                        <span class="text-xs text-gray-500">Current</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div id="productFilesList" class="hidden mt-3 space-y-2"></div>
                @endif
                <input type="file" name="file[]" id="filesInput" multiple class="hidden">
            </div>
        </div>
        <script>
        (function() {
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
        <div class="flex justify-end gap-4 px-4 sm:px-0">
            <a href="{{ route('admin.products.index') }}" class="px-6 sm:px-8 py-3 text-sm font-medium bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>
            <button type="submit" class="px-6 sm:px-8 py-3 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Product</button>
        </div>
    </form>
</div>
</x-admin.app-layout>
