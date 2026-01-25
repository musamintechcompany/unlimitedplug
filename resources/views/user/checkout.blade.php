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
                    
                    <!-- Flutterwave Payment Only -->
                    <div id="payment-method-section" class="border border-gray-200 rounded-lg p-4 mb-6">
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
                    
                    <!-- Coupon Code -->
                    <div id="coupon-section" class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div id="coupon-input-section">
                            <label class="block text-sm font-semibold mb-2">Have a coupon code?</label>
                            <div class="flex gap-2">
                                <input type="text" id="coupon-code" placeholder="Enter code" class="flex-1 border rounded px-3 py-2 text-sm uppercase">
                                <button id="apply-btn" onclick="applyCoupon()" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">Apply</button>
                            </div>
                            <div id="coupon-message" class="mt-2 text-sm hidden"></div>
                        </div>
                        <div id="coupon-applied" class="hidden">
                            <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded px-3 py-2">
                                <span class="text-sm text-green-700">✓ Coupon applied: <strong id="applied-code"></strong></span>
                                <button onclick="removeCoupon()" class="text-red-600 hover:text-red-800 text-xs font-medium">Remove</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center text-sm">
                                <span>Subtotal:</span>
                                <span id="subtotal">{{ $currencySymbol }}0.00</span>
                            </div>
                            <div id="discount-row" class="hidden flex justify-between items-center text-sm text-green-600">
                                <span>Discount:</span>
                                <span id="discount">-{{ $currencySymbol }}0.00</span>
                            </div>
                            <div class="flex justify-between items-center font-semibold text-lg border-t pt-2">
                                <span>Total:</span>
                                <span id="total" data-currency="{{ $currency }}">{{ $currencySymbol }}0.00</span>
                            </div>
                        </div>
                        <button onclick="processPayment()" id="checkout-btn" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold">
                            Pay Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.payment-warning')

    <script>

        // Load checkout items
        function loadCheckoutItems() {
            fetch('/cart/items')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('checkout-items');
                    const subtotalEl = document.getElementById('subtotal');
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
                    
                    subtotalEl.textContent = `${data.currencySymbol}${data.total}`;
                    totalEl.textContent = `${data.currencySymbol}${data.total}`;
                    window.cartSubtotal = parseFloat(data.total);
                    window.currencySymbol = data.currencySymbol;
                    
                    // Hide payment method and coupon for free orders
                    if (parseFloat(data.total) === 0) {
                        document.getElementById('payment-method-section').style.display = 'none';
                        document.getElementById('coupon-section').style.display = 'none';
                        document.getElementById('checkout-btn').textContent = 'Access Now';
                    }
                })
                .catch(error => console.error('Error loading checkout items:', error));
        }
        
        // Apply coupon
        function applyCoupon() {
            const codeInput = document.getElementById('coupon-code');
            const code = codeInput.value.trim();
            const applyBtn = document.getElementById('apply-btn');
            
            if (!code) {
                showMessage('Please enter a coupon code', 'error');
                return;
            }
            
            applyBtn.disabled = true;
            applyBtn.textContent = 'Applying...';
            
            fetch('/coupon/apply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('applied-code').textContent = code.toUpperCase();
                    document.getElementById('coupon-input-section').classList.add('hidden');
                    document.getElementById('coupon-applied').classList.remove('hidden');
                    updateTotals(data.discount, data.newTotal, data.currencySymbol);
                } else {
                    showMessage(data.message, 'error');
                    applyBtn.disabled = false;
                    applyBtn.textContent = 'Apply';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Failed to apply coupon', 'error');
                applyBtn.disabled = false;
                applyBtn.textContent = 'Apply';
            });
        }
        
        // Remove coupon
        function removeCoupon() {
            fetch('/coupon/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('coupon-applied').classList.add('hidden');
                document.getElementById('coupon-input-section').classList.remove('hidden');
                document.getElementById('discount-row').classList.add('hidden');
                document.getElementById('total').textContent = `${window.currencySymbol}${window.cartSubtotal.toFixed(2)}`;
                
                document.getElementById('coupon-code').value = '';
                document.getElementById('apply-btn').disabled = false;
                document.getElementById('apply-btn').textContent = 'Apply';
            });
        }
        
        // Update totals
        function updateTotals(discount, newTotal, currencySymbol) {
            document.getElementById('discount').textContent = `-${currencySymbol}${discount.toFixed(2)}`;
            document.getElementById('discount-row').classList.remove('hidden');
            document.getElementById('total').textContent = `${currencySymbol}${newTotal.toFixed(2)}`;
        }
        
        // Show message
        function showMessage(message, type) {
            const messageEl = document.getElementById('coupon-message');
            messageEl.textContent = message;
            messageEl.className = `mt-2 text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
            messageEl.classList.remove('hidden');
            setTimeout(() => messageEl.classList.add('hidden'), 3000);
        }
        
        // Process payment
        function processPayment() {
            const totalEl = document.getElementById('total');
            const totalText = totalEl.textContent.replace(/[^0-9.]/g, '');
            const amount = parseFloat(totalText);
            
            // If free order, process directly without modal
            if (amount === 0) {
                const button = document.querySelector('#checkout-btn');
                button.disabled = true;
                button.textContent = 'Processing...';
                processFreeOrder(button);
            } else {
                // Show modal for paid orders
                document.getElementById('payment-modal').classList.remove('hidden');
            }
        }
        
        function closePaymentModal() {
            document.getElementById('payment-modal').classList.add('hidden');
        }
        
        function confirmPayment() {
            closePaymentModal();
            
            const button = document.querySelector('button[onclick="processPayment()"]');
            const totalEl = document.getElementById('total');
            const currency = totalEl.dataset.currency;
            
            // Get total amount
            const totalText = totalEl.textContent.replace(/[^0-9.]/g, '');
            const amount = parseFloat(totalText);
            
            if (amount < 0) {
                alert('Invalid amount. Please refresh and try again.');
                return;
            }
            
            button.disabled = true;
            button.textContent = 'Processing...';
            
            // If total is 0, process as free order
            if (amount === 0) {
                processFreeOrder(button);
            } else {
                processFlutterwavePayment(amount, button, currency);
            }
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
        
        // Process Free Order
        function processFreeOrder(button) {
            fetch('/order/create-free', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = 'Redirecting...';
                    window.location.href = '/checkout/success';
                } else {
                    alert('Order creation failed: ' + (data.message || 'Unknown error'));
                    resetButton(button);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Order creation failed. Please try again.');
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