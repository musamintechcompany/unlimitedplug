<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Digital Asset
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('digital-assets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('title')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Subcategory</label>
                                <input type="text" name="subcategory" value="{{ old('subcategory') }}" required 
                                       placeholder="e.g., ecommerce, portfolio, react"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('subcategory')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Demo URL (Optional)</label>
                                <input type="url" name="demo_url" value="{{ old('demo_url') }}" 
                                       placeholder="https://demo.example.com"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('demo_url')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea name="description" rows="4" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                            @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Pricing -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Pricing</h3>
                            
                            <!-- USD Pricing -->
                            <div class="mb-6">
                                <h4 class="text-md font-medium mb-3 text-blue-600">USD Pricing (Required)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        <!-- Files -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Preview Image</label>
                                <input type="file" name="preview_image" accept="image/*" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('preview_image')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Download File (Optional)</label>
                                <input type="file" name="download_file" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('download_file')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Gallery Images (Optional)</label>
                            <input type="file" name="gallery_images[]" accept="image/*" multiple 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('gallery_images.*')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Tags (comma separated)</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" 
                                   placeholder="responsive, modern, clean, bootstrap"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tags')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Features -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Features (one per line)</label>
                            <textarea name="features" rows="4" 
                                      placeholder="Responsive design&#10;Cross-browser compatible&#10;SEO optimized"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('features') }}</textarea>
                            @error('features')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Requirements -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Requirements (Optional)</label>
                            <textarea name="requirements" rows="3" 
                                      placeholder="PHP 8.0+, Laravel 10+, MySQL 5.7+"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('requirements') }}</textarea>
                            @error('requirements')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('digital-assets.index') }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Create Asset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>