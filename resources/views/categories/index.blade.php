<x-guest1-layout>
    <x-slot name="title">{{ $category->name }} - Unlimited Plug</x-slot>
    <x-slot name="description">{{ $category->description }}</x-slot>
    @if($category->tag)
    <x-slot name="keywords">{{ $category->tag }}</x-slot>
    @endif

    <section class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $category->name }}</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto opacity-90">{{ $category->description }}</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($products->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No products available in this category at the moment.</p>
            </div>
        @else
            <div class="grid gap-7" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                @foreach($products as $product)
                    <div class="rounded-xl transition-all duration-200 hover:border hover:border-gray-300 cursor-pointer p-1 group" onclick="window.location.href='/c/{{ $category->slug }}/{{ $product['id'] }}'">
                        <div class="relative w-full h-52 rounded-xl overflow-hidden mb-2.5">
                            <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-full h-full object-cover">
                            <button onclick="toggleFavorite('{{ $product['id'] }}', this); event.stopPropagation();" class="absolute top-2 right-2 p-2 rounded-full bg-white hover:bg-gray-100 shadow-lg favorite-btn z-10 transition-opacity" data-product-id="{{ $product['id'] }}" data-favorited="false">
                                <svg class="w-5 h-5 text-gray-600 favorite-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="px-1">
                            <div class="text-sm text-gray-900 dark:text-white leading-snug mb-1.5">{{ $product['title'] }}</div>
                            @if($product['is_featured'])
                                <div class="text-xs font-semibold text-blue-600 uppercase mb-1">FEATURED</div>
                            @endif
                            <div class="flex items-center gap-2 mb-1">
                                @if($product['oldPrice'])
                                    <span class="text-xs text-gray-400 line-through">{{ $product['currencySymbol'] }}{{ number_format($product['oldPrice'], 2) }}</span>
                                @endif
                                @if($product['price'] == 0)
                                <span class="text-sm font-bold text-green-600">FREE</span>
                                @else
                                <span class="text-sm font-bold text-black dark:text-white">{{ $product['currencySymbol'] }}{{ number_format($product['price'], 2) }}</span>
                                @endif
                                @if($product['oldPrice'])
                                    <span class="text-xs font-bold text-red-600">({{ round((($product['oldPrice'] - $product['price']) / $product['oldPrice']) * 100) }}% OFF)</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1.5 mb-2.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                </svg>
                                Digital Download
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="event.stopPropagation(); addToCart('{{ $product['id'] }}')" class="flex-1 py-2 px-4 rounded-full border border-black dark:border-white bg-white dark:bg-gray-800 hover:bg-black dark:hover:bg-white hover:text-white dark:hover:text-black transition-colors text-xs font-medium">
                                    + Add to cart
                                </button>
                                @if($product['demo_url'])
                                    <a href="{{ $product['demo_url'] }}" target="_blank" onclick="event.stopPropagation();" class="flex-1 text-center py-2 px-4 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-xs font-medium text-gray-700 dark:text-gray-300">
                                        Preview
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
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

        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ asset_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Item added to cart!');
                    updateCartCount(data.cartCount);
                } else {
                    showNotification(data.message || 'Failed to add to cart', 'error');
                }
            })
            .catch(() => showNotification('An error occurred', 'error'));
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-2 py-1 rounded text-xs shadow-lg z-50 transition-all duration-200 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 1500);
        }

        function updateCartCount(count) {
            const cartBadges = document.querySelectorAll('.cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'block' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadFavoriteStates();
        });
    </script>
</x-guest1-layout>
