<x-guest1-layout>
    <x-slot name="title">Software - Unlimited Plug</x-slot>
    <x-slot name="description">Premium software solutions for business and personal use. Buy or rent the tools you need.</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Premium Software Solutions</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto opacity-90">
                Discover professional software tools for business and personal use. Buy or rent the applications you need to boost your productivity.
            </p>
        </div>
    </section>

    <!-- Software Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div id="software-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @if($products->isEmpty())
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No software products available at the moment.</p>
                </div>
            @else
                @foreach($products as $product)
                    <div class="cursor-pointer border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition">
                        <div class="relative mb-3">
                            <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-full h-40 object-cover">
                        </div>
                        <div class="p-4">
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Software</span>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-1 mb-2">{{ $product['title'] }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">{{ Str::limit($product['description'], 80) }}</p>
                            <div class="flex items-center mb-3">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($product['rating']))
                                        <span class="text-yellow-400">★</span>
                                    @else
                                        <span class="text-gray-300">☆</span>
                                    @endif
                                @endfor
                                <span class="text-sm text-gray-500 ml-1">({{ $product['reviews'] }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-lg font-bold text-blue-600">{{ $product['currencySymbol'] }}{{ number_format($product['price'], 2) }}</div>
                                    @if($product['oldPrice'])
                                        <div class="text-sm text-gray-500 line-through">{{ $product['currencySymbol'] }}{{ number_format($product['oldPrice'], 2) }}</div>
                                    @endif
                                </div>
                                <button onclick="addToCart({{ $product['id'] }})" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-guest1-layout>