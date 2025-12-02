<x-guest1-layout>
    <x-slot name="title">How It Works - Unlimited Plug</x-slot>
    <x-slot name="description">Learn how to get started with Unlimited Plug in three simple steps.</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-blue-900 via-blue-900 to-gray-900 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">How It Works</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto opacity-90">
                Get started with Unlimited Plug in three simple steps. It's easy to find, purchase, and use the tools you need.
            </p>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Browse & Choose</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Explore our marketplace of premium software and templates. Use filters and search to find exactly what you need for your project.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Browse categories like Software, Templates, and Digital Products
                        </p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Buy or Rent</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Choose to purchase outright or rent monthly. Pay securely with cryptocurrency or traditional payment methods.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Crypto payments supported: Bitcoin, Ethereum, and 300+ coins
                        </p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Download & Use</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Instantly access your purchase. Download files, get license keys, and start using your new tools right away.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Lifetime access to downloads and updates included
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                Join thousands of users who trust Unlimited Plug for their software and template needs.
            </p>
            <a href="{{ route('marketplace') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Browse Marketplace
            </a>
        </div>
    </section>
</x-guest1-layout>