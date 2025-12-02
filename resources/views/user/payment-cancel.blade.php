<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto text-center">
            <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Cancelled</h1>
            <p class="text-gray-600 mb-6">Your payment was cancelled. Your cart items are still saved.</p>
            <a href="/checkout" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Try Again
            </a>
        </div>
    </div>
</x-app-layout>