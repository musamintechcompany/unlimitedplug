<x-marketplace-layout>
    <x-slot name="title">{{ $product['title'] }} - Unlimited Plug</x-slot>
    <x-slot name="description">{{ Str::limit(strip_tags($product['description']), 150) }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-gray-600">
            <a href="/" class="hover:text-blue-600">Home</a>
            <span class="mx-2">›</span>
            <a href="/marketplace" class="hover:text-blue-600">Marketplace</a>
            <span class="mx-2">›</span>
            <span class="text-gray-900">{{ $product['title'] }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Image Gallery - Left Column -->
            <div class="lg:col-span-7">
                <div class="sticky top-4">
                    <!-- Main Image -->
                    <div class="relative bg-white rounded-lg overflow-hidden mb-4 border border-gray-200">
                        <button onclick="toggleFavorite('{{ $product['id'] }}', this)" class="absolute top-4 right-4 z-10 bg-white hover:bg-gray-50 p-3 rounded-full shadow-lg favorite-btn" data-product-id="{{ $product['id'] }}">
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
                        <div class="flex-shrink-0 w-20 h-20 bg-white rounded border-2 border-blue-600 cursor-pointer overflow-hidden" onclick="changeImage('{{ $product['image'] }}', this)">
                            <img src="{{ $product['image'] }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </div>
                        @foreach($product['media'] as $media)
                        <div class="flex-shrink-0 w-20 h-20 bg-white rounded border-2 border-gray-200 hover:border-blue-600 cursor-pointer overflow-hidden" onclick="changeImage('{{ $media }}', this)">
                            <img src="{{ $media }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-4 text-right">
                        <a href="#" class="text-sm text-gray-600 hover:text-gray-900 inline-flex items-center gap-1">
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
                        <span class="text-sm text-gray-600">Designed by</span>
                        <a href="#" class="text-sm font-semibold text-gray-900 hover:text-blue-600">{{ $product['seller'] ?? 'UnlimitedPlug' }}</a>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-yellow-400 text-sm">{{ $i <= floor($product['rating']) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold">{{ number_format($product['rating'], 1) }}</span>
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

                @if(isset($product['in_carts']) && $product['in_carts'] > 0)
                <p class="text-sm text-red-600 font-semibold mb-3">In {{ $product['in_carts'] }} carts</p>
                @endif

                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-baseline gap-3 mb-2">
                        @if($product['price'] == 0)
                        <span class="text-3xl font-bold text-green-600">FREE</span>
                        @else
                        <span class="text-3xl font-bold text-gray-900">{{ $product['currencySymbol'] }}{{ number_format($product['price'], 2) }}</span>
                        @endif
                        @if($product['oldPrice'])
                        <span class="text-xl text-gray-400 line-through">{{ $product['currencySymbol'] }}{{ number_format($product['oldPrice'], 2) }}</span>
                        @php
                            $percentageOff = round((($product['oldPrice'] - $product['price']) / $product['oldPrice']) * 100);
                        @endphp
                        <span class="text-lg font-bold text-red-600">({{ $percentageOff }}% OFF)</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600">VAT Included</p>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-semibold text-gray-900 mb-6 leading-tight">{{ $product['title'] }}</h1>

                <!-- Add to Cart -->
                <div class="mb-6">
                    <button onclick="addToCart('{{ $product['id'] }}')" class="w-full bg-black hover:bg-gray-800 text-white px-6 py-4 rounded-full font-semibold text-base transition-colors">
                        Add to cart
                    </button>
                </div>

                <!-- Item Details -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <button onclick="toggleSection('item-details')" class="w-full flex items-center justify-between text-left mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Item details</h2>
                        <svg class="w-5 h-5 transform transition-transform" id="item-details-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="item-details" class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Highlights</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.5 8v7H3v5h5v-1.25h2.5v-2H8V15H6.5V8H8V6.5h7V8h1.75v2.457l2 1.714V8H20V3h-5v1.5H8V3H3v5z"></path><path d="m12.39 9.129 9.273 7.971-4.17.29 1.378 3-2.272 1.043-1.36-2.962-2.854 2.887z"></path></svg>
                                    <span class="text-gray-700">Designed by <a href="#" class="font-semibold hover:underline">{{ $product['seller'] ?? 'UnlimitedPlug' }}</a></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 12.32a7 7 0 0 0 0-.82A7.5 7.5 0 0 0 8.71 5.73a6.63 6.63 0 0 1 3.06 1.75c.13.12.24.26.36.39l-.89.89A6 6 0 1 0 7 19h12.5a3.5 3.5 0 0 0 1.5-6.68m-9 5.35-3.51-2.11 1-1.72 1.49.89V11h2v3.73l1.49-.89 1 1.72z"></path></svg>
                                    <span class="text-gray-700">Digital download</span>
                                </li>
                                {{-- <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2c2.21 0 4 1.79 4 4v10.5c0 3.03-2.47 5.5-5.5 5.5A5.51 5.51 0 0 1 7 16.5V7c0-.55.45-1 1-1s1 .45 1 1v9.5c0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5V6c0-1.1-.9-2-2-2s-2 .9-2 2v9.5c0 .28.22.5.5.5s.5-.22.5-.5V7c0-.55.45-1 1-1s1 .45 1 1v8.5a2.5 2.5 0 0 1-5 0V6c0-2.21 1.79-4 4-4"></path></svg>
                                    <span class="text-gray-700">Digital file type(s): 1 PDF</span>
                                </li> --}}
                            </ul>
                        </div>

                        <div class="pt-4">
                            <div class="text-gray-700 leading-relaxed max-h-48 overflow-hidden" id="description-text">
                                {!! $product['description'] !!}
                            </div>
                            <button onclick="toggleReadMore()" class="text-sm text-gray-900 hover:underline mt-2 font-semibold" id="read-more-btn">
                                Learn more about this item
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delivery -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <button onclick="toggleSection('delivery')" class="w-full flex items-center justify-between text-left mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Delivery</h2>
                        <svg class="w-5 h-5 transform transition-transform" id="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="delivery">
                        <p class="text-2xl font-semibold text-gray-900 mb-3">Instant Download</p>
                        <p class="text-gray-700 mb-3">Your files will be available to download once payment is confirmed. <a href="#" class="text-blue-600 hover:underline">Here's how.</a></p>
                        <p class="text-gray-600 text-sm">Instant download items don't accept returns, exchanges or cancellations. Please contact the seller about any problems with your order.</p>
                    </div>
                </div>

                <!-- Did You Know -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <button onclick="toggleSection('did-you-know')" class="w-full flex items-center justify-between text-left mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Did you know?</h2>
                        <svg class="w-5 h-5 transform transition-transform" id="did-you-know-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="did-you-know">
                        <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                            <svg class="w-12 h-12 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 48 48">
                                <path d="M32.3,15.1L23,19.8c-1.7,1.2-4.2.8-5.4-.9v0c-1.2-1.7-.8-4.1.9-5.4c2.1-1.5,4.7-3.4,5.6-3.9C28.7,6.7,35,7.4,39,11.4v0"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Purchase Protection</p>
                                <p class="text-sm text-gray-600">Shop confidently knowing if something goes wrong with an order, we've got your back for all eligible purchases — <a href="#" class="text-blue-600 hover:underline">see program terms</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meet Your Sellers -->
                <div class="border-t border-gray-200 pt-6">
                    <button onclick="toggleSection('meet-sellers')" class="w-full flex items-center justify-between text-left mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Meet your sellers</h2>
                        <svg class="w-5 h-5 transform transition-transform rotate-180" id="meet-sellers-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="meet-sellers" class="hidden">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-black rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center">
                                <img src="{{ asset('images/logos/logo1.png') }}" alt="UnlimitedPlug" class="w-10 h-10 object-contain">
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $product['seller'] ?? 'UnlimitedPlug' }}</p>
                                <p class="text-sm text-gray-600">Owner of <a href="#" class="hover:underline">UnlimitedPlugShop</a></p>
                                <button class="text-sm text-gray-900 hover:underline flex items-center gap-1 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    Follow shop
                                </button>
                            </div>
                        </div>
                        <a href="https://wa.me/{{ config('services.whatsapp.support_number') }}?text={{ urlencode('Hi, I\'m interested in: ' . $product['title'] . ' - ' . url()->current()) }}" target="_blank" class="w-full border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 px-6 py-3 rounded-full font-semibold transition-colors inline-block text-center">
                            Message {{ $product['seller'] ?? 'Seller' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-12">
            <div class="bg-white border border-gray-200 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Reviews</h2>
                @if(count($reviews) > 0)
                    <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                        @foreach($reviews as $review)
                        <div class="flex-shrink-0 w-96 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($review['user_name'], 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $review['user_name'] }}</h4>
                                    <div class="flex items-center gap-2">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }} text-sm">★</span>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review['created_at'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 line-clamp-4">{{ $review['comment'] }}</p>
                                </div>
                                @if(!empty($review['images']))
                                <div class="flex-shrink-0 w-20">
                                    <img src="{{ Storage::url($review['images'][0]) }}" alt="Review image" class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-75" onclick="openFullscreenImage('{{ Storage::url($review['images'][0]) }}')">
                                    @if(count($review['images']) > 1)
                                    <div class="text-xs text-center text-gray-500 mt-1">+{{ count($review['images']) - 1 }} more</div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14.782 8.676 12 2.145l-2.78 6.53-7.086.625 5.364 4.663-1.595 6.918L12 17.228l6.097 3.653-1.596-6.919L21.867 9.3z"></path></svg>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Be the first to review this item</h3>
                                <p class="text-gray-600">No reviews yet. See what customers say about other items from this shop.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">You may also like</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($relatedProducts as $related)
                <a href="/marketplace/product/{{ $related['id'] }}" class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                        <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2">{{ $related['title'] }}</h3>
                        <div class="flex items-center justify-between">
                            @if($related['price'] == 0)
                            <span class="text-lg font-bold text-green-600">FREE</span>
                            @else
                            <span class="text-lg font-bold text-gray-900">{{ $related['currencySymbol'] }}{{ number_format($related['price'], 2) }}</span>
                            @endif
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="text-yellow-400 mr-1">★</span>
                                <span>{{ number_format($related['rating'], 1) }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
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
            document.querySelectorAll('#thumbnail-container > div, .flex.gap-2 > div').forEach(el => {
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

        function openFullscreenImage(imageSrc) {
            const modal = document.getElementById('fullscreen-modal');
            const fullscreenImage = document.getElementById('fullscreen-image');
            fullscreenImage.src = imageSrc;
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
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
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

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
    </style>
</x-marketplace-layout>
