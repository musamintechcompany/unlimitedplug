<x-admin.app-layout>
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col space-y-4 md:flex-row md:justify-between md:items-center md:space-y-0">
            <div>
                <h1 class="text-xl md:text-3xl font-bold text-gray-900">Categories & Subcategories</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1 md:mt-2">Manage product categories and subcategories</p>
            </div>
            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-3">
                <button onclick="openCreateCategoryModal()" 
                        class="bg-blue-600 text-white px-3 py-2 md:px-4 text-sm md:text-base rounded-md hover:bg-blue-700 w-full md:w-auto">
                    Create Category
                </button>
                <button onclick="openCreateSubcategoryModal()" 
                        class="bg-green-600 text-white px-3 py-2 md:px-4 text-sm md:text-base rounded-md hover:bg-green-700 w-full md:w-auto">
                    Create Subcategory
                </button>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="mt-4">
            <input type="text" id="searchInput" onkeyup="searchCategories()" placeholder="Search categories..." 
                   class="w-full md:w-96 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Categories List -->
    <div class="space-y-6" id="categoriesList">
        @foreach($categories as $category)
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                            @if($category->subcategories->count() > 0)
                                <button onclick="toggleSubcategories('{{ $category->id }}')" 
                                        class="flex items-center space-x-1 text-sm text-gray-500 hover:text-gray-700">
                                    <span>({{ $category->subcategories->count() }} subcategories)</span>
                                    <svg id="toggle-icon-{{ $category->id }}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            @else
                                <span class="text-sm text-gray-400">(No subcategories)</span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editCategory('{{ $category->id }}', '{{ $category->name }}', '{{ $category->description }}', {{ $category->is_active ? 'true' : 'false' }}, {{ $category->sort_order }}, '{{ $category->tag }}')" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">Edit</button>
                            <form method="POST" action="{{ route('admin.categories.destroy-category', $category) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure? This will delete all subcategories too.')" 
                                        class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                    @if($category->description)
                        <p class="text-gray-600 mt-1">{{ $category->description }}</p>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                        {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Collapsible Subcategories -->
            @if($category->subcategories->count() > 0)
                <div id="subcategories-{{ $category->id }}" class="hidden border-t pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($category->subcategories as $subcategory)
                        <div class="bg-gray-50 rounded-lg p-3 flex justify-between items-center">
                            <div>
                                <div class="font-medium text-sm text-gray-900">{{ $subcategory->name }}</div>
                                @if($subcategory->description)
                                    <div class="text-xs text-gray-500 mt-1">{{ $subcategory->description }}</div>
                                @endif
                            </div>
                            <div class="flex space-x-1">
                                <button onclick="editSubcategory('{{ $subcategory->id }}', '{{ $category->id }}', '{{ $subcategory->name }}', '{{ $subcategory->description }}', {{ $subcategory->is_active ? 'true' : 'false' }}, {{ $subcategory->sort_order }})" 
                                        class="text-blue-600 hover:text-blue-900 text-xs">Edit</button>
                                <form method="POST" action="{{ route('admin.categories.destroy-subcategory', $subcategory) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" 
                                            class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<!-- Create Category Modal -->
<div id="createCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Create New Category</h3>
            </div>
            <form method="POST" action="{{ route('admin.categories.store-category') }}">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" name="name" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SEO Tags (Optional)</label>
                        <input type="text" name="tag" placeholder="e.g. software, apps, digital tools"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Comma-separated keywords for SEO</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateCategoryModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Subcategory Modal -->
<div id="createSubcategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Create New Subcategory</h3>
            </div>
            <form method="POST" action="{{ route('admin.categories.store-subcategory') }}">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                        <select name="category_id" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subcategory Name</label>
                        <input type="text" name="name" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateSubcategoryModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">
                        Create Subcategory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Edit Category</h3>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf @method('PUT')
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" name="name" id="editCategoryName" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="editCategoryDescription" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SEO Tags (Optional)</label>
                        <input type="text" name="tag" id="editCategoryTag" placeholder="e.g. software, apps, digital tools"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Comma-separated keywords for SEO</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" id="editCategorySortOrder" min="0" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="editCategoryActive" value="1" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditCategoryModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subcategory Modal -->
<div id="editSubcategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Edit Subcategory</h3>
            </div>
            <form id="editSubcategoryForm" method="POST">
                @csrf @method('PUT')
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                        <select name="category_id" id="editSubcategoryCategory" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subcategory Name</label>
                        <input type="text" name="name" id="editSubcategoryName" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="editSubcategoryDescription" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" id="editSubcategorySortOrder" min="0" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="editSubcategoryActive" value="1" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditSubcategoryModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Update Subcategory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateCategoryModal() {
    document.getElementById('createCategoryModal').classList.remove('hidden');
}

function closeCreateCategoryModal() {
    document.getElementById('createCategoryModal').classList.add('hidden');
}

function openCreateSubcategoryModal() {
    document.getElementById('createSubcategoryModal').classList.remove('hidden');
}

function closeCreateSubcategoryModal() {
    document.getElementById('createSubcategoryModal').classList.add('hidden');
}

function editCategory(id, name, description, isActive, sortOrder, tag) {
    document.getElementById('editCategoryForm').action = `/management/portal/admin/categories/category/${id}`;
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryDescription').value = description || '';
    document.getElementById('editCategoryTag').value = tag || '';
    document.getElementById('editCategorySortOrder').value = sortOrder || 0;
    document.getElementById('editCategoryActive').checked = isActive;
    document.getElementById('editCategoryModal').classList.remove('hidden');
}

function closeEditCategoryModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
}

function editSubcategory(id, categoryId, name, description, isActive, sortOrder) {
    document.getElementById('editSubcategoryForm').action = `/management/portal/admin/categories/subcategory/${id}`;
    document.getElementById('editSubcategoryCategory').value = categoryId;
    document.getElementById('editSubcategoryName').value = name;
    document.getElementById('editSubcategoryDescription').value = description || '';
    document.getElementById('editSubcategorySortOrder').value = sortOrder || 0;
    document.getElementById('editSubcategoryActive').checked = isActive;
    document.getElementById('editSubcategoryModal').classList.remove('hidden');
}

function closeEditSubcategoryModal() {
    document.getElementById('editSubcategoryModal').classList.add('hidden');
}

function toggleSubcategories(categoryId) {
    const subcategoriesDiv = document.getElementById(`subcategories-${categoryId}`);
    const toggleIcon = document.getElementById(`toggle-icon-${categoryId}`);
    
    if (subcategoriesDiv.classList.contains('hidden')) {
        subcategoriesDiv.classList.remove('hidden');
        toggleIcon.style.transform = 'rotate(180deg)';
    } else {
        subcategoriesDiv.classList.add('hidden');
        toggleIcon.style.transform = 'rotate(0deg)';
    }
}

function searchCategories() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const categoriesList = document.getElementById('categoriesList');
    const categories = categoriesList.getElementsByClassName('bg-white');
    
    for (let i = 0; i < categories.length; i++) {
        const categoryName = categories[i].querySelector('h3').textContent.toLowerCase();
        if (categoryName.includes(filter)) {
            categories[i].style.display = '';
        } else {
            categories[i].style.display = 'none';
        }
    }
}
</script>
</x-admin.app-layout>