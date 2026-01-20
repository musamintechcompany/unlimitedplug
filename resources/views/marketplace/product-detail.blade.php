<x-marketplace-layout>
    <x-slot name="title">Product Details - Unlimited Plug</x-slot>
    <x-slot name="description">View detailed information about this digital product.</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <button onclick="window.history.back()" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
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
                        <h1 id="product-title" class="text-3xl font-bold text-gray-900 dark:text-white"></h1>
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
                    <button id="preview-btn" class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview Demo
                    </button>
                </div>

                <!-- Tabs -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                    <div id="tabs-nav" class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
                        <button class="tab-btn active px-4 py-2 text-blue-600 border-b-2 border-blue-600 font-semibold" data-tab="details">Details</button>
                        <button id="features-tab-btn" class="tab-btn hidden px-4 py-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold" data-tab="features">Features</button>
                        <button class="tab-btn px-4 py-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold" data-tab="reviews">Reviews</button>
                    </div>

                    <!-- Tab Content -->
                    <div id="tab-details" class="tab-content">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Product Details</h4>
                        <div id="detailed-description" class="text-gray-600 dark:text-gray-400 leading-relaxed prose max-w-none"></div>
                        <div id="requirements-section" class="hidden mt-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Requirements</h4>
                            <div id="requirements-content" class="text-gray-600 dark:text-gray-400 leading-relaxed prose max-w-none"></div>
                        </div>
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
            document.getElementById('product-title').textContent = product.title;
            document.getElementById('product-price').textContent = `${product.currencySymbol}${product.price.toFixed(2)}`;
            document.getElementById('product-description').innerHTML = product.description;
            document.getElementById('detailed-description').innerHTML = product.description;

            // Set old price if exists
            if (product.oldPrice) {
                const oldPriceEl = document.getElementById('old-price');
                oldPriceEl.textContent = `${product.currencySymbol}${product.oldPrice.toFixed(2)}`;
                oldPriceEl.classList.remove('hidden');
            }

            // Set rating
            document.getElementById('product-stars').innerHTML = createStarsHTML(product.rating);
            const reviewText = product.reviews === 1 ? 'review' : 'reviews';
            document.getElementById('review-count').textContent = `(${product.reviews} ${reviewText})`;

            // Set features
            const featuresList = document.getElementById('features-list');
            const featuresTabBtn = document.getElementById('features-tab-btn');
            featuresList.innerHTML = '';
            if (product.features && product.features.length > 0) {
                featuresTabBtn.classList.remove('hidden');
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
            }

            // Set requirements
            if (product.requirements) {
                const requirementsSection = document.getElementById('requirements-section');
                const requirementsContent = document.getElementById('requirements-content');
                requirementsSection.classList.remove('hidden');
                
                // Try to parse as array or split by newlines/commas
                let requirementsList = [];
                try {
                    requirementsList = JSON.parse(product.requirements);
                } catch {
                    // If not JSON, split by newlines or commas
                    requirementsList = product.requirements.split(/[\n,]/).filter(r => r.trim());
                }
                
                if (Array.isArray(requirementsList) && requirementsList.length > 0) {
                    requirementsContent.innerHTML = '<ul class="space-y-2">' + requirementsList.map(req => `
                        <li class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${req.trim()}
                        </li>
                    `).join('') + '</ul>';
                } else {
                    // Fallback to HTML display
                    requirementsContent.innerHTML = product.requirements;
                }
            }

            // Set preview button
            if (product.demo_url) {
                const previewBtn = document.getElementById('preview-btn');
                previewBtn.classList.remove('hidden');
                previewBtn.onclick = () => window.open(product.demo_url, '_blank');
            }

            // Set reviews
            const reviewsList = document.getElementById('reviews-list');
            const reviews = @json($reviews);
            
            if (reviews.length === 0) {
                reviewsList.innerHTML = '<p class="text-gray-500">No reviews yet. Be the first to review this product!</p>';
            } else {
                reviewsList.innerHTML = reviews.map(review => `
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                    ${review.user_name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 dark:text-white">${review.user_name}</h5>
                                    <div class="flex items-center">
                                        ${createStarsHTML(review.rating)}
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">${review.created_at}</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">${review.comment}</p>
                    </div>
                `).join('');
            }
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