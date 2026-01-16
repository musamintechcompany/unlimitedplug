<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>
            
            @php
                $currency = session('currency', 'USD');
                $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
            @endphp
            
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
                    
                    @if($currency === 'NGN')
                        <!-- Paystack Payment -->
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 cursor-pointer hover:border-blue-500 transition" onclick="selectPayment('paystack')">
                            <div class="flex items-center mb-2">
                                <input type="radio" name="payment" value="paystack" id="paystack" checked class="text-blue-600">
                                <label for="paystack" class="ml-3 flex items-center cursor-pointer">
                                    <svg class="h-8" viewBox="0 0 120 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.5 0C3.8 0 0 3.8 0 8.5V21.5C0 26.2 3.8 30 8.5 30H21.5C26.2 30 30 26.2 30 21.5V8.5C30 3.8 26.2 0 21.5 0H8.5ZM15 7C18.9 7 22 10.1 22 14C22 17.9 18.9 21 15 21C11.1 21 8 17.9 8 14C8 10.1 11.1 7 15 7Z" fill="#00C3F7"/>
                                        <text x="35" y="20" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#00C3F7">Paystack</text>
                                    </svg>
                                </label>
                            </div>
                            <div class="ml-6 text-xs text-gray-600">
                                <p class="text-xs">Pay securely with Paystack</p>
                                <p class="text-[10px] text-gray-500">Cards, Bank Transfer, USSD</p>
                            </div>
                        </div>
                        
                        <!-- Flutterwave Payment -->
                        <div class="border border-gray-200 rounded-lg p-4 mb-6 cursor-pointer hover:border-blue-500 transition" onclick="selectPayment('flutterwave')">
                            <div class="flex items-center mb-2">
                                <input type="radio" name="payment" value="flutterwave" id="flutterwave" class="text-blue-600">
                                <label for="flutterwave" class="ml-3 flex items-center cursor-pointer">
                                    <img src="https://flutterwave.com/images/logo/full.svg" alt="Flutterwave" class="h-10">
                                </label>
                            </div>
                            <div class="ml-6 text-xs text-gray-600">
                                <p class="text-xs">Pay securely with Flutterwave</p>
                                <p class="text-[10px] text-gray-500">Cards, Bank Transfer, Mobile Money</p>
                            </div>
                        </div>
                    @else
                        <!-- Flutterwave Payment Only -->
                        <div class="border border-gray-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center mb-2">
                                <input type="radio" name="payment" value="flutterwave" checked class="text-blue-600">
                                <label class="ml-3 flex items-center">
                                    <img src="https://flutterwave.com/images/logo/full.svg" alt="Flutterwave" class="h-10">
                                </label>
                            </div>
                            <div class="ml-6 text-xs text-gray-600">
                                <p class="text-xs">Pay securely with Flutterwave</p>
                                <p class="text-[10px] text-gray-500">Supports 150+ currencies worldwide</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold">Total:</span>
                            <span id="total" class="font-bold text-lg" data-currency="{{ $currency }}">{{ $currencySymbol }}0.00</span>
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
        // Select payment method
        function selectPayment(method) {
            document.getElementById(method).checked = true;
        }
        
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
            const currency = totalEl.dataset.currency;
            const selectedPayment = document.querySelector('input[name="payment"]:checked').value;
            
            // Get total amount
            const totalText = totalEl.textContent.replace(/[^0-9.]/g, '');
            const amount = parseFloat(totalText);
            
            if (!amount || amount <= 0) {
                alert('Invalid amount. Please refresh and try again.');
                return;
            }
            
            button.disabled = true;
            button.textContent = 'Processing...';
            
            if (selectedPayment === 'paystack') {
                processPaystackPayment(amount, button);
            } else {
                processFlutterwavePayment(amount, button, currency);
            }
        }
        
        // Process Paystack Payment
        function processPaystackPayment(amount, button) {
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
                    resetButton(button);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment failed. Please try again.');
                resetButton(button);
            });
        }
        
        // Process Flutterwave Payment
        function processFlutterwavePayment(amount, button, currency) {
            fetch('/flutterwave/initialize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: @auth '{{ auth()->user()->email }}' @else 'guest@example.com' @endauth,
                    amount: amount,
                    currency: currency
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.payment_url) {
                    button.textContent = 'Redirecting...';
                    window.location.href = data.payment_url;
                } else {
                    alert('Payment initialization failed: ' + (data.message || 'Unknown error'));
                    resetButton(button);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment failed. Please try again.');
                resetButton(button);
            });
        }
        
        function resetButton(button) {
            button.disabled = false;
            button.textContent = 'Pay Now';
        }
        
        // Load items on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadCheckoutItems();
        });
    </script>
</x-app-layout>