<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">My Favorites</h1>

            @if($favorites->count() > 0)
                <div class="grid gap-7" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                    @foreach($favorites as $product)
                        <div class="rounded-xl transition-all duration-200 hover:border hover:border-gray-300 cursor-pointer p-1" onclick="window.location.href='/marketplace/product/{{ $product['id'] }}'">
                            <div class="relative w-full h-52 rounded-xl overflow-hidden mb-2.5">
                                <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-full h-full object-cover">
                                <button onclick="toggleFavorite('{{ $product['id'] }}', this); event.stopPropagation();" class="absolute top-2 right-2 p-1.5 rounded-full bg-white/80 hover:bg-white transition-colors favorite-btn z-10" data-product-id="{{ $product['id'] }}">
                                    <svg class="w-4 h-4 text-red-500 fill-current favorite-icon" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-1">
                                <div class="text-sm text-gray-900 leading-snug mb-1.5">{{ $product['title'] }}</div>
                                <div class="flex items-center gap-2 mb-1">
                                    @if($product['oldPrice'])
                                        <span class="text-xs text-gray-400 line-through">{{ $product['currencySymbol'] }}{{ number_format($product['oldPrice'], 2) }}</span>
                                    @endif
                                    <span class="text-sm font-bold text-black">{{ $product['currencySymbol'] }}{{ number_format($product['price'], 2) }}</span>
                                </div>
                                <div class="text-xs text-gray-600 flex items-center gap-1.5 mb-2.5">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                    </svg>
                                    Digital Download
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="addToCart('{{ $product['id'] }}'); event.stopPropagation();" class="flex-1 py-2 px-4 rounded-full border border-black bg-white hover:bg-black hover:text-white transition-colors text-xs font-medium">
                                        + Add to cart
                                    </button>
                                    @if($product['demo_url'])
                                        <a href="{{ $product['demo_url'] }}" target="_blank" onclick="event.stopPropagation();" class="flex-1 text-center py-2 px-4 rounded-full border border-gray-300 bg-white hover:bg-gray-50 transition-colors text-xs font-medium text-gray-700">Preview</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">No favorites yet</p>
                    <a href="{{ route('marketplace') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        Browse Marketplace
                    </a>
                </div>
            @endif
        </div>
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
            .then(response => response.json())
            .then(data => {
                if (!data.favorited) {
                    button.closest('.cursor-pointer').remove();
                    if (document.querySelectorAll('.cursor-pointer').length === 0) {
                        location.reload();
                    }
                }
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
                    alert('Item added to cart!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to add to cart'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding to cart');
            });
        }
    </script>
</x-app-layout>
