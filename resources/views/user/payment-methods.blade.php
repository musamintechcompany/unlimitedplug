<x-app-layout>


    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Payment History -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Payment History</h3>

                    <div id="payment-history" class="space-y-4">
                        <!-- Payment history will be loaded here -->
                        <div class="py-8 text-center text-gray-500">
                            No payments yet
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Load payment history
        function loadPaymentHistory() {
            const container = document.getElementById('payment-history');
            container.innerHTML = '<div class="py-8 text-center text-gray-500">No payments yet</div>';
        }

        // Show notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Load payment history on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadPaymentHistory();
        });
    </script>
</x-app-layout>
