<!-- Desktop Search Bar -->
<div class="hidden lg:block mb-6">
    <div class="relative">
        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" id="desktop-search-input" placeholder="Search products, templates, plugins..." 
               class="w-full pl-10 pr-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded focus:outline-none">
    </div>
</div>

<!-- Mobile Search & Categories Button -->
<div class="lg:hidden mb-6">
    <div class="flex gap-2">
        <!-- Categories Button -->
        <div class="relative">
            <button id="mobile-categories-btn" class="flex items-center gap-2 px-4 py-2 bg-gray-100 border border-gray-300 rounded text-gray-700 hover:bg-gray-200 text-sm font-medium" onclick="toggleMobileDropdown()">
                All
            </button>
            
            <!-- Mobile Categories Dropdown -->
            <div id="mobile-categories-dropdown-unique" class="hidden absolute top-12 left-0 w-64 bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto z-50 mobile-dropdown-scroll">
                <div class="p-2">
                    <a href="#" class="block px-3 py-2 text-sm text-blue-600 font-medium hover:bg-gray-50 rounded" onclick="setActiveMobileDropdown(this); window.showAllProducts(); return false;">All Categories</a>
                    
                    @php
                        $categories = \App\Models\Category::with('subcategories')->where('is_active', true)->orderBy('sort_order')->get();
                    @endphp
                    
                    @foreach($categories as $category)
                        @if($category->subcategories->count() > 0)
                            <div class="mt-2">
                                <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded" onclick="setActiveMobileDropdown(this); window.filterByCategory('{{ $category->name }}'); return false;">{{ $category->name }}</a>
                                <div class="ml-4 mt-1 space-y-1">
                                    @foreach($category->subcategories as $subcategory)
                                        <a href="#" class="block px-3 py-1 text-xs text-gray-600 hover:bg-gray-50 rounded" onclick="setActiveMobileDropdown(this); window.filterBySubcategory('{{ $subcategory->name }}'); return false;">• {{ $subcategory->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-2">
                                <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded" onclick="setActiveMobileDropdown(this); window.filterByCategory('{{ $category->name }}'); return false;">{{ $category->name }}</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="flex-1 relative">
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="mobile-search-input" placeholder="Search products, templates, plugins..." 
                   class="w-full pl-10 pr-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded focus:outline-none">
        </div>
    </div>
</div>

<!-- Desktop Categories -->
<ul class="hidden lg:block space-y-1">
    <li><a href="#" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300" onclick="setActiveCategory(this); window.showAllProducts(); return false;">All Categories</a></li>
    
    @php
        $categories = \App\Models\Category::with('subcategories')->where('is_active', true)->orderBy('sort_order')->get();
    @endphp
    
    @foreach($categories as $category)
        @if($category->subcategories->count() > 0)
            <li>
                <button class="w-full flex items-center justify-between px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" onclick="toggleSubcategory('{{ $category->id }}'); setActiveCategory(this); window.filterByCategory('{{ $category->name }}')">
                    <span>{{ $category->name }}</span>
                    <svg id="{{ $category->id }}-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <ul id="{{ $category->id }}-sub" class="hidden mt-1 space-y-1">
                    @foreach($category->subcategories as $subcategory)
                        <li><a href="#" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" onclick="setActiveCategory(this); window.filterBySubcategory('{{ $subcategory->name }}'); return false;">{{ $subcategory->name }}</a></li>
                    @endforeach
                </ul>
            </li>
        @else
            <li><a href="#" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" onclick="setActiveCategory(this); window.filterByCategory('{{ $category->name }}'); return false;">{{ $category->name }}</a></li>
        @endif
    @endforeach
</ul>



<style>
    .flex::-webkit-scrollbar {
        display: none;
    }
    
    .mobile-dropdown-scroll::-webkit-scrollbar {
        width: 4px;
    }
    
    .mobile-dropdown-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    
    .mobile-dropdown-scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }
    
    .mobile-dropdown-scroll::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<script>
    // Setup search functionality
    document.addEventListener('DOMContentLoaded', () => {
        // Wait for products to be available
        const waitForProducts = () => {
            if (window.products && window.renderProducts) {
                setupSearch();
            } else {
                setTimeout(waitForProducts, 100);
            }
        };
        waitForProducts();
    });
    
    function setupSearch() {
        const desktopSearch = document.getElementById('desktop-search-input');
        const mobileSearch = document.getElementById('mobile-search-input');
        const searchInputs = [desktopSearch, mobileSearch].filter(input => input);
        
        searchInputs.forEach(searchInput => {
            // Search on Enter key
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    window.performSearch(e.target);
                }
            });
            
            // Real-time search (debounced)
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => window.performSearch(e.target), 300);
            });
        });
    }
    
    // Perform search - Make it globally available immediately
    window.performSearch = function(inputElement) {
        const query = inputElement.value.trim();
        const products = window.products || [];
        
        console.log('Search query:', query);
        console.log('Products available:', products.length);
        
        // Sync both search inputs
        const desktopSearch = document.getElementById('desktop-search-input');
        const mobileSearch = document.getElementById('mobile-search-input');
        [desktopSearch, mobileSearch].forEach(input => {
            if (input && input !== inputElement) {
                input.value = query;
            }
        });
        
        if (query === '') {
            if (window.renderProducts) window.renderProducts(products);
            return;
        }
        
        // Local search through products
        const searchResults = products.filter(product => {
            const searchText = query.toLowerCase();
            return product.title.toLowerCase().includes(searchText) ||
                   product.description.toLowerCase().includes(searchText) ||
                   product.type.toLowerCase().includes(searchText) ||
                   (product.subcategory && product.subcategory.toLowerCase().includes(searchText)) ||
                   (product.tags && product.tags.toLowerCase().includes(searchText));
        });
        
        console.log('Search results:', searchResults.length);
        
        if (window.renderProducts) window.renderProducts(searchResults);
    };
    
    function toggleSubcategory(category) {
        const submenu = document.getElementById(category + '-sub');
        const icon = document.getElementById(category + '-icon');
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            icon.style.transform = 'rotate(90deg)';
        } else {
            submenu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
    
    function setActiveCategory(element) {
        // Remove active class from all categories
        document.querySelectorAll('.lg\\:block a, .lg\\:block button').forEach(el => {
            el.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300');
        });
        
        // Add active class to clicked element
        element.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300');
    }
    
    function setActiveMobileCategory(element) {
        // Remove active class from all mobile categories
        document.querySelectorAll('.lg\\:hidden a').forEach(el => {
            el.classList.remove('bg-blue-600', 'text-white');
            el.classList.add('bg-white', 'border', 'border-gray-200', 'text-gray-700');
        });
        
        // Add active class to clicked element
        element.classList.remove('bg-white', 'border', 'border-gray-200', 'text-gray-700');
        element.classList.add('bg-blue-600', 'text-white');
    }
    
    // Filter by main category
    function filterByCategory(category) {
        const products = window.products || [];
        const filteredProducts = products.filter(product => 
            product.category && product.category.toLowerCase() === category.toLowerCase()
        );
        if (window.renderProducts) window.renderProducts(filteredProducts);
    }
    
    // Filter by subcategory
    function filterBySubcategory(subcategory) {
        const products = window.products || [];
        console.log('Subcategory clicked:', subcategory);
        console.log('Product subcategories:', products.map(p => p.subcategory));
        
        const filteredProducts = products.filter(product => product.subcategory === subcategory);
        console.log('Filtered by subcategory:', filteredProducts);
        
        if (window.renderProducts) window.renderProducts(filteredProducts);
    }
    
    // Show all products
    function showAllProducts() {
        const products = window.products || [];
        if (window.renderProducts) window.renderProducts(products);
    }
    
    // Simple mobile dropdown toggle
    function toggleMobileDropdown() {
        // Find the mobile dropdown specifically (not the desktop one)
        const dropdowns = document.querySelectorAll('[id="mobile-categories-dropdown-unique"]');
        const mobileDropdown = dropdowns[dropdowns.length - 1]; // Get the last one (mobile)
        if (mobileDropdown) {
            mobileDropdown.classList.toggle('hidden');
        }
    }
    
    // Make sure function is available immediately
    window.toggleMobileDropdown = toggleMobileDropdown;
    
    function toggleMobileSubcategory(categoryId) {
        const submenu = document.getElementById(categoryId + '-sub');
        const icon = document.getElementById(categoryId + '-icon');
        
        if (!submenu || !icon) return;
        
        const isHidden = submenu.classList.contains('hidden');
        
        if (isHidden) {
            submenu.classList.remove('hidden');
            icon.style.transform = 'rotate(90deg)';
        } else {
            submenu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
    
    function setActiveMobileDropdown(element) {
        // Remove active class from all dropdown items
        document.querySelectorAll('#mobile-categories-dropdown-unique a, #mobile-categories-dropdown-unique button').forEach(el => {
            el.classList.remove('bg-blue-100', 'text-blue-600', 'font-medium');
        });
        
        // Add active class to clicked element
        element.classList.add('bg-blue-100', 'text-blue-600', 'font-medium');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id="mobile-categories-dropdown-unique"]');
        const dropdown = dropdowns[dropdowns.length - 1]; // Get the last one (mobile)
        const button = event.target.closest('#mobile-categories-btn');
        
        if (dropdown && !dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Make functions globally available
    window.toggleSubcategory = toggleSubcategory;
    window.toggleMobileSubcategory = toggleMobileSubcategory;
    window.setActiveCategory = setActiveCategory;
    window.setActiveMobileCategory = setActiveMobileCategory;
    window.setActiveMobileDropdown = setActiveMobileDropdown;
    window.toggleMobileDropdown = toggleMobileDropdown;
    window.filterByCategory = filterByCategory;
    window.filterBySubcategory = filterBySubcategory;
    window.showAllProducts = showAllProducts;
</script>