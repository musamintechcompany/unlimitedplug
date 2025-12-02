<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    <div id="checkout-items" class="space-y-4">
                        <!-- Items will be loaded here -->
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Payment Method</h2>
                    
                    <div class="border border-gray-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center mb-3">
                            <input type="radio" name="payment" value="paystack" checked class="text-blue-600">
                            <label class="ml-3 text-sm font-medium text-gray-700">Card Payment (Paystack)</label>
                        </div>
                        <div class="ml-6 text-sm text-gray-600">
                            <p>Pay securely with your debit/credit card</p>
                            <p>Supports Visa, Mastercard, and local Nigerian cards</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold">Total:</span>
                            <span id="total" class="font-bold text-lg">₦0.00</span>
                        </div>
                        <button onclick="processPayment()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold">
                            Pay Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load checkout items
        function loadCheckoutItems() {
            fetch('/cart/items')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('checkout-items');
                    const totalEl = document.getElementById('total');
                    
                    if (data.items.length === 0) {
                        container.innerHTML = '<p class="text-gray-500 text-center py-8">No items in cart</p>';
                        return;
                    }
                    
                    container.innerHTML = data.items.map(item => `
                        <div class="flex items-center py-3 border-b gap-3">
                            <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded border flex-shrink-0">
                            <div class="flex-1">
                                <h4 class="font-medium">${item.name}</h4>
                                <p class="text-sm text-gray-500">Qty: ${item.quantity}</p>
                            </div>
                            <p class="font-semibold">${item.currencySymbol}${(item.price * item.quantity).toFixed(2)}</p>
                        </div>
                    `).join('');
                    
                    totalEl.textContent = `${data.currencySymbol}${data.total}`;
                })
                .catch(error => console.error('Error loading checkout items:', error));
        }
        
        // Process payment
        function processPayment() {
            const button = event.target;
            const totalEl = document.getElementById('total');
            
            // Get total amount (remove currency symbol and convert to number)
            const totalText = totalEl.textContent.replace(/[^0-9.]/g, '');
            const amount = parseFloat(totalText);
            
            if (!amount || amount <= 0) {
                alert('Invalid amount. Please refresh and try again.');
                return;
            }
            
            button.disabled = true;
            button.textContent = 'Processing...';
            
            fetch('/paystack/initialize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: @auth '{{ auth()->user()->email }}' @else 'guest@example.com' @endauth,
                    amount: amount,
                    currency: 'NGN'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.authorization_url) {
                    button.textContent = 'Redirecting...';
                    window.location.href = data.authorization_url;
                } else {
                    alert('Payment initialization failed: ' + (data.message || 'Unknown error'));
                    resetButton();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment failed. Please try again.');
                resetButton();
            });
            
            function resetButton() {
                button.disabled = false;
                button.textContent = 'Pay Now';
            }
        }
        
        // Load items on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadCheckoutItems();
        });
    </script>
</x-app-layout>