<x-marketplace-layout>
    <x-slot name="title">Product Details - Unlimited Plug</x-slot>
    <x-slot name="description">View detailed information about this digital product.</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <button onclick="history.back()" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </button>
        </div>

        <!-- Product Detail Container -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Product Gallery -->
            <div class="h-96 bg-gray-200 dark:bg-gray-700">
                <img id="main-image" src="" alt="" class="w-full h-full object-cover">
            </div>

            <!-- Product Info -->
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span id="product-type" class="text-sm font-semibold text-blue-600 uppercase tracking-wide"></span>
                        <h1 id="product-title" class="text-3xl font-bold text-gray-900 dark:text-white mt-2"></h1>
                    </div>
                    <div class="text-right">
                        <div id="old-price" class="text-lg text-gray-500 line-through hidden"></div>
                        <div id="product-price" class="text-2xl font-bold text-blue-600"></div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="flex items-center mb-6">
                    <div id="product-stars" class="flex items-center"></div>
                    <span id="review-count" class="text-sm text-gray-500 ml-2"></span>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                    <p id="product-description" class="text-gray-600 dark:text-gray-400 leading-relaxed"></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <button onclick="addToCart()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Add to Cart
                    </button>
                    <button class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Buy Now
                    </button>
                    <button class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Preview
                    </button>
                </div>

                <!-- Tabs -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
                        <button class="tab-btn active px-4 py-2 text-blue-600 border-b-2 border-blue-600 font-semibold" data-tab="details">Details</button>
                        <button class="tab-btn px-4 py-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold" data-tab="features">Features</button>
                        <button class="tab-btn px-4 py-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold" data-tab="reviews">Reviews</button>
                    </div>

                    <!-- Tab Content -->
                    <div id="tab-details" class="tab-content">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Product Details</h4>
                        <p id="detailed-description" class="text-gray-600 dark:text-gray-400 leading-relaxed"></p>
                    </div>

                    <div id="tab-features" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Features</h4>
                        <ul id="features-list" class="space-y-2 text-gray-600 dark:text-gray-400">
                            <!-- Features will be loaded here -->
                        </ul>
                    </div>

                    <div id="tab-reviews" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Customer Reviews</h4>
                        <div id="reviews-list" class="space-y-4">
                            <!-- Reviews will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real product data from server
        const productData = @json($product);

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            loadProductData();
            setupTabs();
        });

        // Load product data
        function loadProductData() {
            const product = productData;
            if (!product) return;

            // Set basic info
            document.getElementById('main-image').src = product.image;
            document.getElementById('main-image').alt = product.title;
            document.getElementById('product-type').textContent = product.type.toUpperCase();
            document.getElementById('product-title').textContent = product.title;
            document.getElementById('product-price').textContent = `$${product.price}`;
            document.getElementById('product-description').textContent = product.description;
            document.getElementById('detailed-description').textContent = product.description;

            // Set old price if exists
            if (product.oldPrice) {
                const oldPriceEl = document.getElementById('old-price');
                oldPriceEl.textContent = `$${product.oldPrice}`;
                oldPriceEl.classList.remove('hidden');
            }

            // Set rating
            document.getElementById('product-stars').innerHTML = createStarsHTML(product.rating);
            document.getElementById('review-count').textContent = `(${product.reviews} downloads)`;

            // Set features
            const featuresList = document.getElementById('features-list');
            featuresList.innerHTML = '';
            if (product.features && product.features.length > 0) {
                product.features.forEach(feature => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center';
                    li.innerHTML = `
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        ${feature}
                    `;
                    featuresList.appendChild(li);
                });
            } else {
                featuresList.innerHTML = '<li class="text-gray-500">No features listed</li>';
            }

            // Set placeholder reviews
            const reviewsList = document.getElementById('reviews-list');
            reviewsList.innerHTML = '<p class="text-gray-500">No reviews yet. Be the first to review this product!</p>';
        }

        // Create stars HTML
        function createStarsHTML(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            let starsHTML = '';
            
            for (let i = 0; i < fullStars; i++) {
                starsHTML += '<span class="text-yellow-400">★</span>';
            }
            
            if (hasHalfStar) {
                starsHTML += '<span class="text-yellow-400">☆</span>';
            }
            
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                starsHTML += '<span class="text-gray-300">☆</span>';
            }
            
            return starsHTML;
        }

        // Setup tabs
        function setupTabs() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabId = btn.dataset.tab;

                    // Update active tab button
                    tabBtns.forEach(b => {
                        b.classList.remove('active', 'text-blue-600', 'border-blue-600');
                        b.classList.add('text-gray-500');
                    });
                    btn.classList.add('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
                    btn.classList.remove('text-gray-500');

                    // Update active tab content
                    tabContents.forEach(content => content.classList.add('hidden'));
                    document.getElementById(`tab-${tabId}`).classList.remove('hidden');
                });
            });
        }
        
        // Add to cart function
        function addToCart() {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ asset_id: productData.id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Item added to cart!');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showNotification('Error adding item to cart', 'error');
            });
        }
        
        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-2 rounded shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</x-marketplace-layout>