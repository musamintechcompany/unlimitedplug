<x-marketplace-layout>
    <x-slot name="title">UnlimitedPlug Marketplace - Buy Digital Products, Physical Goods & Services</x-slot>
    <x-slot name="description">Shop at UnlimitedPlug.com marketplace for everything. Browse digital products, physical goods, and services from creators worldwide. Unlimited Plug - Your trusted online marketplace.</x-slot>
    <x-slot name="keywords">unlimitedplug, unlimited plug, unlimitedplug marketplace, unlimited plug marketplace, marketplace for everything, digital products, physical goods, services, buy online</x-slot>
    <x-slot name="type">website</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-blue-900 via-blue-900 to-gray-900 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">UnlimitedPlug - Your Marketplace for Everything</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto opacity-90">
                Explore. Discover. Collect. Browse unique items from creators worldwide at Unlimited Plug marketplace.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Categories (Desktop) -->
            <div class="hidden lg:block lg:w-56 flex-shrink-0">
                <div class="sticky top-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Categories</h2>
                    <x-marketplace-categories />
                </div>
            </div>
            
            <!-- Mobile Categories -->
            <div class="lg:hidden mb-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Categories</h2>
                <div>
                    <x-marketplace-categories />
                </div>
            </div>
            
            <!-- Products Section -->
            <div class="flex-1">
                <div id="products-grid" class="grid gap-7" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                    <!-- Products will be loaded here -->
                </div>
                
                <!-- No Results -->
                <div id="no-results" class="hidden text-center py-12">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No products found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search or browse our categories.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real products data from server
        const products = @json($products);

        // DOM elements
        const productsGrid = document.getElementById('products-grid');
        const noResults = document.getElementById('no-results');

        // Make data and functions globally available immediately
        window.products = products;
        window.renderProducts = renderProducts;
        window.filterByCategory = filterByCategory;
        window.filterBySubcategory = filterBySubcategory;
        window.showAllProducts = showAllProducts;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Check if category was selected from home page
            const selectedCategory = sessionStorage.getItem('selectedCategory');
            if (selectedCategory) {
                // Clear the stored category
                sessionStorage.removeItem('selectedCategory');
                // Filter by that category
                filterByCategory(selectedCategory);
            } else {
                renderProducts(products);
            }
            
            // Load favorite states for all products
            loadFavoriteStates();
        });

        // Render products
        function renderProducts(productsToRender) {
            productsGrid.innerHTML = '';
            
            if (productsToRender.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }
            
            noResults.classList.add('hidden');
            
            productsToRender.forEach(product => {
                const productCard = createProductCard(product);
                productsGrid.appendChild(productCard);
            });
            
            // Load favorite states after rendering
            loadFavoriteStates();
        }

        // Create product card
        function createProductCard(product) {
            const card = document.createElement('div');
            card.className = 'rounded-xl transition-all duration-200 hover:border hover:border-gray-300 cursor-pointer p-1 group';
            
            card.innerHTML = `
                <div class="relative w-full h-52 rounded-xl overflow-hidden mb-2.5">
                    <img src="${product.image}" alt="${product.title}" class="w-full h-full object-cover">
                    <button onclick="toggleFavorite('${product.id}', this); event.stopPropagation();" class="absolute top-2 right-2 p-2 rounded-full bg-white hover:bg-gray-100 shadow-lg favorite-btn z-10 transition-opacity" data-product-id="${product.id}" data-favorited="false">
                        <svg class="w-5 h-5 text-gray-600 favorite-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
                <div class="px-1">
                    <div class="text-sm text-gray-900 leading-snug mb-1.5">${product.title}</div>
                    ${product.badge ? `<div class="text-xs font-semibold text-blue-600 uppercase mb-1">${product.badge}</div>` : ''}
                    <div class="flex items-center gap-2 mb-1">
                        ${product.oldPrice ? `<span class="text-xs text-gray-400 line-through">${product.currencySymbol}${product.oldPrice}</span>` : ''}
                        <span class="text-sm font-bold text-black">${product.currencySymbol}${product.price}</span>
                        ${product.percentageOff ? `<span class="text-xs font-bold text-red-600">(${product.percentageOff}% OFF)</span>` : ''}
                    </div>
                    <div class="text-xs text-gray-600 flex items-center gap-1.5 mb-2.5">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                        </svg>
                        Digital Download
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="addToCart('${product.id}'); event.stopPropagation();" class="flex-1 py-2 px-4 rounded-full border border-black bg-white hover:bg-black hover:text-white transition-colors text-xs font-medium">
                            + Add to cart
                        </button>
                        ${product.demo_url ? `<a href="${product.demo_url}" target="_blank" onclick="event.stopPropagation();" class="flex-1 text-center py-2 px-4 rounded-full border border-gray-300 bg-white hover:bg-gray-50 transition-colors text-xs font-medium text-gray-700">Preview</a>` : ''}
                    </div>
                </div>
            `;
            
            card.addEventListener('click', () => {
                window.location.href = `/marketplace/product/${product.id}`;
            });
            
            return card;
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


        
        // Filter by main category (shows all products in that category)
        function filterByCategory(category) {
            const filteredProducts = products.filter(product => 
                product.category && product.category.toLowerCase() === category.toLowerCase()
            );
            renderProducts(filteredProducts);
        }
        
        // Filter by subcategory (shows only products in that subcategory)
        function filterBySubcategory(subcategory) {
            const filteredProducts = products.filter(product => product.subcategory === subcategory);
            renderProducts(filteredProducts);
        }
        
        // Show all products
        function showAllProducts() {
            renderProducts(products);
        }
        
        // Add to cart function
        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ asset_id: productId })
            })
            .then(response => {
                return response.json().then(data => ({ status: response.status, data }));
            })
            .then(({ status, data }) => {
                if (status === 200 && data.success) {
                    updateCartCount(data.cartCount);
                    showNotification('Item added to cart!');
                } else {
                    console.error('Cart error:', data);
                    showNotification(data.message || 'Error adding item to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showNotification('Error adding item to cart', 'error');
            });
        }
        
        // Update cart count in navigation
        function updateCartCount(count) {
            const cartBadges = document.querySelectorAll('.cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'block' : 'none';
            });
        }
        
        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-2 py-1 rounded text-xs shadow-lg z-50 transition-all duration-200 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 1500);
        }
        
        // Toggle cart sidebar
        function toggleCartSidebar() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            
            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                loadCartItems();
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }
        
        // Load cart items for sidebar
        function loadCartItems() {
            fetch('/cart/items')
                .then(response => response.json())
                .then(data => {
                    const cartItems = document.getElementById('cart-items');
                    const cartTotal = document.getElementById('cart-total');
                    
                    if (data.items.length === 0) {
                        cartItems.innerHTML = '<p class="text-gray-500 text-center">Your cart is empty</p>';
                        cartTotal.textContent = `${data.currencySymbol || '$'}0.00`;
                    } else {
                        cartItems.innerHTML = data.items.map(item => `
                            <div class="flex items-center justify-between py-3 border-b">
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm">${item.name}</h4>
                                    <p class="text-gray-500 text-xs">${item.type}</p>
                                    <p class="text-blue-600 font-semibold">${item.currencySymbol}${item.price}</p>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})" class="text-gray-500 hover:text-gray-700 p-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span class="text-sm px-2">${item.quantity}</span>
                                    <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})" class="text-gray-500 hover:text-gray-700 p-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                    <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700 p-1 ml-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `).join('');
                        cartTotal.textContent = `${data.currencySymbol}${data.total}`;
                    }
                })
                .catch(error => console.error('Error loading cart items:', error));
        }
        
        // Update quantity function
        function updateQuantity(cartId, newQuantity) {
            if (newQuantity < 1) {
                removeFromCart(cartId);
                return;
            }
            
            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart_id: cartId, quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCartItems();
                    updateCartCount(data.cartCount);
                }
            })
            .catch(error => console.error('Error updating quantity:', error));
        }
        
        // Remove from cart function
        function removeFromCart(cartId) {
            fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart_id: cartId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCartItems();
                    updateCartCount(data.cartCount);
                    showNotification('Item removed from cart');
                }
            })
            .catch(error => console.error('Error removing from cart:', error));
        }
        
        // Handle checkout button click
        function handleCheckout() {
            @auth
                window.location.href = '/checkout';
            @else
                // Dispatch event to open login modal
                window.dispatchEvent(new CustomEvent('open-login-modal'));
            @endauth
        }
        
        // Make cart icon clickable
        document.addEventListener('DOMContentLoaded', () => {
            // Add click event to cart icons
            document.querySelectorAll('.cart-icon').forEach(icon => {
                icon.addEventListener('click', toggleCartSidebar);
            });
            
            // Load cart count on page load
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => updateCartCount(data.count))
                .catch(error => console.error('Error loading cart count:', error));
        });
        
        // Toggle favorite function
        function toggleFavorite(productId, button) {
            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const icon = button.querySelector('.favorite-icon');
                if (data.favorited) {
                    icon.classList.add('text-red-500', 'fill-current');
                    icon.classList.remove('text-gray-400');
                    button.dataset.favorited = 'true';
                    button.classList.remove('opacity-0', 'group-hover:opacity-100');
                    button.classList.add('opacity-100');
                    if (data.isGuest) {
                        showGuestFavoriteWarning();
                    } else {
                        showNotification('Added to favorites!');
                    }
                } else {
                    icon.classList.remove('text-red-500', 'fill-current');
                    icon.classList.add('text-gray-400');
                    button.dataset.favorited = 'false';
                    button.classList.add('opacity-0', 'group-hover:opacity-100');
                    button.classList.remove('opacity-100');
                    showNotification('Removed from favorites');
                }
            })
            .catch(error => {
                console.error('Error toggling favorite:', error);
                showNotification('Error updating favorites', 'error');
            });
        }
        
        // Load favorite states
        function loadFavoriteStates() {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');
            favoriteButtons.forEach(button => {
                const productId = button.dataset.productId;
                fetch(`/favorites/check?product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        const icon = button.querySelector('.favorite-icon');
                        if (data.favorited) {
                            icon.classList.add('text-red-500', 'fill-current');
                            icon.classList.remove('text-gray-400');
                            button.dataset.favorited = 'true';
                            button.classList.remove('opacity-0', 'group-hover:opacity-100');
                            button.classList.add('opacity-100');
                        } else {
                            button.dataset.favorited = 'false';
                            button.classList.add('opacity-0', 'group-hover:opacity-100');
                            button.classList.remove('opacity-100');
                        }
                    })
                    .catch(error => console.error('Error loading favorite state:', error));
            });
        }

    </script>
    
    <!-- Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-96 lg:w-[500px] bg-white border-l border-gray-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-lg font-semibold">Shopping Cart</h2>
                <button onclick="toggleCartSidebar()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Cart Items -->
            <div id="cart-items" class="flex-1 overflow-y-auto p-4">
                <p class="text-gray-500 text-center">Your cart is empty</p>
            </div>
            
            <!-- Footer -->
            <div class="border-t p-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-semibold">Total:</span>
                    <span id="cart-total" class="font-bold text-lg">$0.00</span>
                </div>
                <button onclick="handleCheckout()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded font-semibold">
                    Checkout
                </button>
            </div>
        </div>
    </div>
    
    <!-- Cart Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleCartSidebar()"></div>
</x-marketplace-layout>