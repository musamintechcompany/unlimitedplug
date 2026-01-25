<x-guest1-layout>
    <x-slot name="title">How It Works - Unlimited Plug</x-slot>
    <x-slot name="description">Learn how to get started with Unlimited Plug in three simple steps.</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-blue-900 to-gray-900 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">How It Works</h1>
            <p class="text-lg opacity-90">Three simple steps to get started</p>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="py-8 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="space-y-4">
                <!-- Step 1 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleStep(1)" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold text-white">1</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Browse</h3>
                        </div>
                        <svg id="arrow-1" class="w-6 h-6 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div id="step-1" class="hidden px-6 pb-6">
                        <p class="text-gray-600 mb-3">Explore our marketplace and discover what you need. Use categories, filters, and search to find exactly what you're looking for. Save your favorites to review later.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Browse by category and subcategory</li>
                            <li>• Filter by price, rating, and features</li>
                            <li>• Read reviews from other buyers</li>
                            <li>• Save favorites for later</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleStep(2)" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold text-white">2</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Purchase</h3>
                        </div>
                        <svg id="arrow-2" class="w-6 h-6 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div id="step-2" class="hidden px-6 pb-6">
                        <p class="text-gray-600 mb-3">Add to your cart and complete checkout securely. Choose from multiple payment methods and currencies. Get instant access after payment confirmation.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Secure payment processing</li>
                            <li>• Multiple currency support</li>
                            <li>• Instant purchase confirmation</li>
                            <li>• Order history tracking</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleStep(3)" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold text-white">3</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Download</h3>
                        </div>
                        <svg id="arrow-3" class="w-6 h-6 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div id="step-3" class="hidden px-6 pb-6">
                        <p class="text-gray-600 mb-3">Access your purchases instantly from your account. Download anytime with unlimited access. Get license information and support when needed.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Instant access after purchase</li>
                            <li>• Unlimited downloads</li>
                            <li>• License details included</li>
                            <li>• Access from your account anytime</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleStep(step) {
            const content = document.getElementById(`step-${step}`);
            const arrow = document.getElementById(`arrow-${step}`);
            
            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-90');
        }
    </script>

    <!-- CTA Section -->
    <section class="pt-8 pb-4 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="border border-gray-200 rounded-lg p-8 bg-white text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Start Exploring</h2>
                <p class="text-gray-600 mb-6">Discover what our marketplace has to offer.</p>
                <a href="{{ route('marketplace') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Visit Marketplace
                </a>
            </div>
        </div>
    </section>
</x-guest1-layout>
