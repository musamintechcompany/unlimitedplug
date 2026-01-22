<footer class="bg-gray-900 text-gray-300 py-12 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Brand -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4">Unlimited Plug</h3>
                <p class="text-sm">Your marketplace for everything - digital products, physical goods, and services.</p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('marketplace') }}" class="hover:text-white">Marketplace</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-white">How It Works</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h4 class="text-white font-semibold mb-4">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">Help Center</a></li>
                    <li><a href="#" class="hover:text-white">Contact Us</a></li>
                </ul>
            </div>
            
            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-semibold mb-4">Stay Updated</h4>
                <form id="footer-newsletter-form" class="space-y-2">
                    @csrf
                    <input type="text" name="name" placeholder="Name (optional)" 
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm focus:ring-blue-500 focus:border-blue-500">
                    <input type="email" name="email" required placeholder="Your email" 
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm font-semibold transition">
                        Subscribe
                    </button>
                    <p id="footer-newsletter-msg" class="text-xs hidden"></p>
                </form>
            </div>
        </div>
        
        <div class="border-t border-gray-800 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} Unlimited Plug. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    document.getElementById('footer-newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const msg = document.getElementById('footer-newsletter-msg');
        const btn = form.querySelector('button[type="submit"]');
        
        btn.disabled = true;
        btn.textContent = 'Subscribing...';
        
        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                email: form.email.value,
                name: form.name.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                msg.textContent = '✓ Subscribed successfully!';
                msg.className = 'text-xs text-green-400';
                msg.classList.remove('hidden');
                form.reset();
            }
        })
        .catch(() => {
            msg.textContent = 'Email already subscribed';
            msg.className = 'text-xs text-red-400';
            msg.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Subscribe';
        });
    });
</script>
