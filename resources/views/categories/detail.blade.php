<x-guest1-layout>
    <x-slot name="title">{{ $product['title'] }} - {{ $category->name }} - Unlimited Plug</x-slot>
    <x-slot name="description">{{ Str::limit(strip_tags($product['description']), 150) }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="/" class="hover:text-blue-600 dark:hover:text-blue-400">Home</a>
            <span class="mx-2">›</span>
            <a href="/c/{{ $category->slug }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $category->name }}</a>
            <span class="mx-2">›</span>
            <span class="text-gray-900 dark:text-white">{{ $product['title'] }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Image Gallery - Left Column -->
            <div class="lg:col-span-7">
                <div class="sticky top-4">
                    <!-- Main Image -->
                    <div class="relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden mb-4 border border-gray-200 dark:border-gray-700">
                        <button onclick="toggleFavorite('{{ $product['id'] }}', this)" class="absolute top-4 right-4 z-10 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-full shadow-lg favorite-btn" data-product-id="{{ $product['id'] }}">
                            <svg class="w-6 h-6 favorite-icon text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                        <div class="aspect-[4/3] cursor-pointer" onclick="openFullscreen()">
                            <img id="main-image" src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-full h-full object-contain p-4">
                        </div>
                    </div>
                    
                    <!-- Thumbnails -->
                    @if(isset($product['media']) && count($product['media']) > 0)
                    <div class="flex gap-2 overflow-x-auto pb-2">
                        <div class="flex-shrink-0 w-20 h-20 bg-white dark:bg-gray-800 rounded border-2 border-blue-600 cursor-pointer overflow-hidden" onclick="changeImage('{{ $product['image'] }}', this)">
                            <img src="{{ $product['image'] }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </div>
                        @foreach($product['media'] as $media)
                        <div class="flex-shrink-0 w-20 h-20 bg-white dark:bg-gray-800 rounded border-2 border-gray-200 dark:border-gray-700 hover:border-blue-600 cursor-pointer overflow-hidden" onclick="changeImage('{{ $media }}', this)">
                            <img src="{{ $media }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-4 text-right">
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                            Report this item
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Info - Right Column -->
            <div class="lg:col-span-5">
                <!-- Seller Info -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Designed by</span>
                        <a href="#" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">{{ $product['seller'] ?? 'UnlimitedPlug' }}</a>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-yellow-400 text-sm">{{ $i <= floor($product['rating']) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold dark:text-white">{{ number_format($product['rating'], 1) }}</span>
                        @if(isset($product['badge']) && $product['badge'])
                        <div class="flex items-center gap-1 text-xs font-semibold text-orange-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="m20.902 7.09-2.317-1.332-1.341-2.303H14.56L12.122 2 9.805 3.333H7.122L5.78 5.758 3.341 7.09v2.667L2 12.06l1.341 2.303v2.666l2.318 1.334L7 20.667h2.683L12 22l2.317-1.333H17l1.342-2.303 2.317-1.334v-2.666L22 12.06l-1.341-2.303V7.09zm-6.097 6.062.732 3.515-.488.363-2.927-1.818-3.049 1.697-.488-.363.732-3.516-2.56-2.181.121-.485 3.537-.243 1.341-3.273h.488l1.341 3.273 3.537.243.122.484z"></path></svg>
                            {{ $product['badge'] }}
                        </div>
                        @endif
                        @if(isset($product['is_featured']) && $product['is_featured'])
                        <div class="flex items-center gap-1 text-xs font-semibold text-blue-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="m20.902 7.09-2.317-1.332-1.341-2.303H14.56L12.122 2 9.805 3.333H7.122L5.78 5.758 3.341 7.09v2.667L2 12.06l1.341 2.303v2.666l2.318 1.334L7 20.667h2.683L12 22l2.317-1.333H17l1.342-2.303 2.317-1.334v-2.666L22 12.06l-1.341-2.303V7.09zm-6.097 6.062.732 3.515-.488.363-2.927-1.818-3.049 1.697-.488-.363.732-3.516-2.56-2.181.121-.485 3.537-.243 1.341-3.273h.488l1.341 3.273 3.537.243.122.484z"></path></svg>
                            FEATURED
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-baseline gap-3 mb-2">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product['currencySymbol'] }}{{ number_format($product['price'], 2) }}</span>
                        @if($product['oldPrice'])
                        <span class="text-xl text-gray-400 line-through">{{ $product['currencySymbol'] }}{{ number_format($product['oldPrice'], 2) }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">VAT Included</p>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 leading-tight">{{ $product['title'] }}</h1>

                <!-- Add to Cart -->
                <div class="mb-6">
                    <button onclick="addToCart('{{ $product['id'] }}')" class="w-full bg-black hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-4 rounded-full font-semibold text-base transition-colors">
                        Add to cart
                    </button>
                </div>

                <!-- Item Details -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                    <button onclick="toggleSection('item-details')" class="w-full flex items-center justify-between text-left mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Item details</h2>
                        <svg class="w-5 h-5 transform transition-transform" id="item-details-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="item-details" class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Highlights</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.5 8v7H3v5h5v-1.25h2.5v-2H8V15H6.5V8H8V6.5h7V8h1.75v2.457l2 1.714V8H20V3h-5v1.5H8V3H3v5z"></path><path d="m12.39 9.129 9.273 7.971-4.17.29 1.378 3-2.272 1.043-1.36-2.962-2.854 2.887z"></path></svg>
                                    <span class="text-gray-700 dark:text-gray-300">Designed by <a href="#" class="font-semibold hover:underline">{{ $product['seller'] ?? 'UnlimitedPlug' }}</a></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 12.32a7 7 0 0 0 0-.82A7.5 7.5 0 0 0 8.71 5.73a6.63 6.63 0 0 1 3.06 1.75c.13.12.24.26.36.39l-.89.89A6 6 0 1 0 7 19h12.5a3.5 3.5 0 0 0 1.5-6.68m-9 5.35-3.51-2.11 1-1.72 1.49.89V11h2v3.73l1.49-.89 1 1.72z"></path></svg>
                                    <span class="text-gray-700 dark:text-gray-300">Digital download</span>
                                </li>
                            </ul>
                        </div>

                        <div class="pt-4">
                            <div class="text-gray-700 dark:text-gray-300 leading-relaxed max-h-48 overflow-hidden" id="description-text">
                                {!! nl2br(e($product['description'])) !!}
                            </div>
                            <button onclick="toggleReadMore()" class="text-sm text-gray-900 dark:text-white hover:underline mt-2 font-semibold" id="read-more-btn">
                                Learn more about this item
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delivery -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                    <button onclick="toggleSection('delivery')" class="w-full flex items-center justify-between text-left mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Delivery</h2>
                        <svg class="w-5 h-5 transform transition-transform" id="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="delivery">
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">Instant Download</p>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">Your files will be available to download once payment is confirmed.</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Instant download items don't accept returns, exchanges or cancellations. Please contact the seller about any problems with your order.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        @if(isset($reviews) && count($reviews) > 0)
        <div class="mt-12 max-w-5xl">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Customer Reviews</h2>
                <div class="space-y-6">
                    @foreach($reviews as $review)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                {{ strtoupper(substr($review['user_name'], 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review['user_name'] }}</h4>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review['created_at'] }}</span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300">{{ $review['comment'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Fullscreen Modal -->
    <div id="fullscreen-modal" class="hidden fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center p-4" onclick="closeFullscreen()">
        <button onclick="closeFullscreen()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-white/10 hover:bg-white/20 p-2 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <img id="fullscreen-image" src="" alt="" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
    </div>

    <script>
        function changeImage(src, element) {
            document.getElementById('main-image').src = src;
            document.querySelectorAll('.flex.gap-2 > div').forEach(el => {
                el.classList.remove('border-blue-600');
                el.classList.add('border-gray-200');
            });
            element.classList.add('border-blue-600');
            element.classList.remove('border-gray-200');
        }

        function openFullscreen() {
            const mainImage = document.getElementById('main-image');
            const modal = document.getElementById('fullscreen-modal');
            const fullscreenImage = document.getElementById('fullscreen-image');
            fullscreenImage.src = mainImage.src;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFullscreen() {
            document.getElementById('fullscreen-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const icon = document.getElementById(sectionId + '-icon');
            section.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function toggleReadMore() {
            const text = document.getElementById('description-text');
            const btn = document.getElementById('read-more-btn');
            text.classList.toggle('max-h-48');
            text.classList.toggle('overflow-hidden');
            btn.textContent = text.classList.contains('max-h-48') ? 'Learn more about this item' : 'Show less';
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
                    updateCartCount(data.cartCount);
                    showNotification('Added to cart!', 'success');
                } else {
                    showNotification(data.message || 'Error adding to cart', 'error');
                }
            })
            .catch(error => {
                showNotification('Error adding to cart', 'error');
                console.error('Error:', error);
            });
        }

        function updateCartCount(count) {
            const cartBadges = document.querySelectorAll('.cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            });
        }

        function toggleFavorite(productId, button) {
            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                const icon = button.querySelector('.favorite-icon');
                if (data.favorited) {
                    icon.classList.add('text-red-500', 'fill-current');
                    icon.classList.remove('text-gray-400');
                    if (data.isGuest) {
                        showGuestFavoriteWarning();
                    } else {
                        showNotification('Added to favorites!', 'success');
                    }
                } else {
                    icon.classList.remove('text-red-500', 'fill-current');
                    icon.classList.add('text-gray-400');
                    showNotification('Removed from favorites', 'success');
                }
            })
            .catch(error => console.error('Error:', error));
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

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeFullscreen();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const favoriteBtn = document.querySelector('.favorite-btn');
            if (favoriteBtn) {
                const productId = favoriteBtn.dataset.productId;
                fetch(`/favorites/check?product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.favorited) {
                            const icon = favoriteBtn.querySelector('.favorite-icon');
                            icon.classList.add('text-red-500', 'fill-current');
                            icon.classList.remove('text-gray-400');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
</x-guest1-layout>
