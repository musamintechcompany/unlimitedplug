<!-- Cart Sidebar -->
<div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-96 lg:w-[500px] bg-white border-l border-gray-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-semibold">Shopping Cart</h2>
            <div class="flex items-center gap-2">
                <button id="clear-cart-btn" onclick="clearCart()" class="text-red-500 hover:text-red-700 text-sm font-medium hidden">
                    Clear All
                </button>
                <button onclick="toggleCartSidebar()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
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

<script>
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
                const clearBtn = document.getElementById('clear-cart-btn');
                
                if (data.items.length === 0) {
                    cartItems.innerHTML = '<p class="text-gray-500 text-center">Your cart is empty</p>';
                    cartTotal.textContent = `${data.currencySymbol || '$'}0.00`;
                    clearBtn.classList.add('hidden');
                } else {
                    clearBtn.classList.remove('hidden');
                    cartItems.innerHTML = data.items.map(item => `
                        <div class="flex items-center py-3 border-b gap-3">
                            <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded border flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-sm truncate">${item.name}</h4>
                                <p class="text-gray-500 text-xs">${item.type}</p>
                                <p class="text-blue-600 font-semibold text-sm">${item.currencySymbol || '$'}${item.price}</p>
                            </div>
                            <div class="flex items-center space-x-1 flex-shrink-0">
                                <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})" class="${item.quantity <= 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:text-gray-700'} p-1" ${item.quantity <= 1 ? 'disabled' : ''}>
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
                                <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700 p-1 ml-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `).join('');
                    cartTotal.textContent = `${data.currencySymbol || '$'}${data.total}`;
                }
            })
            .catch(error => console.error('Error loading cart items:', error));
    }
    
    // Update quantity function
    function updateQuantity(cartId, newQuantity) {
        if (newQuantity < 1) {
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
            }
        })
        .catch(error => console.error('Error removing from cart:', error));
    }
    
    // Clear all cart items
    function clearCart() {
        if (!confirm('Are you sure you want to clear all items from your cart?')) {
            return;
        }
        
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCartItems();
                updateCartCount(data.cartCount);
            }
        })
        .catch(error => console.error('Error clearing cart:', error));
    }
    
    // Update cart count in navigation
    function updateCartCount(count) {
        const cartBadges = document.querySelectorAll('.cart-count');
        cartBadges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        });
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
    
    // Load cart count on page load
    document.addEventListener('DOMContentLoaded', () => {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => updateCartCount(data.count))
            .catch(error => console.error('Error loading cart count:', error));
    });
</script>