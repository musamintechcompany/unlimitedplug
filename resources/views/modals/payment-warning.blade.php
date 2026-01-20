<!-- Payment Warning Modal -->
<div id="payment-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <!-- Close X Button -->
        <button onclick="closePaymentModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex items-start mb-4">
            <div class="flex-shrink-0">
                <svg class="h-12 w-12 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Important Notice</h3>
                <p class="text-sm text-gray-600 mb-4">
                    After completing your payment, please wait for the confirmation page to load.
                </p>
                <p class="text-sm text-gray-600 font-medium">
                    ⚠️ Do not close your browser or click the back button until you see the success message.
                </p>
            </div>
        </div>
        <div class="mt-6">
            <button onclick="confirmPayment()" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700">
                I Understand, Continue
            </button>
        </div>
    </div>
</div>
