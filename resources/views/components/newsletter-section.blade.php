<section class="bg-blue-600 py-8">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p id="newsletter-text" class="text-white mb-4 text-sm sm:text-base">Yes! Send me exclusive offers, unique gift ideas, and personalized tips for shopping and selling on UnlimitedPlug.</p>
        <form id="newsletter-form" class="max-w-md mx-auto">
            @csrf
            <input type="hidden" name="step" id="newsletter-step" value="email">
            <input type="hidden" name="email" id="stored-email">
            <div class="relative">
                <input type="email" name="current_input" id="newsletter-input" required placeholder="Enter your email" 
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-white focus:border-white">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-blue-600 hover:text-blue-700 transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                    </svg>
                </button>
            </div>
        </form>
        <p id="newsletter-msg" class="text-sm mt-2 hidden px-2"></p>
    </div>
</section>

<script>
    document.getElementById('newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const step = document.getElementById('newsletter-step').value;
        const input = document.getElementById('newsletter-input');
        const text = document.getElementById('newsletter-text');
        const msg = document.getElementById('newsletter-msg');
        const btn = form.querySelector('button[type="submit"]');
        
        if (step === 'email') {
            // Store email and switch to name input
            document.getElementById('stored-email').value = input.value;
            document.getElementById('newsletter-step').value = 'name';
            input.type = 'text';
            input.value = '';
            input.placeholder = 'Enter your full name';
            input.focus();
            text.textContent = 'Enter your full name to personalize your messages';
        } else {
            // Submit both email and name
            btn.disabled = true;
            
            fetch('{{ route("newsletter.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: document.getElementById('stored-email').value,
                    name: input.value
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            throw new Error(json.error || json.message || 'An error occurred');
                        } catch (e) {
                            throw new Error('Server error. Please check your mail configuration.');
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    msg.textContent = "Great! We've sent you an email to confirm your subscription.";
                    msg.className = 'text-sm mt-2 text-white';
                    msg.classList.remove('hidden');
                    // Hide form and text
                    form.classList.add('hidden');
                    text.classList.add('hidden');
                }
            })
            .catch((error) => {
                msg.textContent = error.message || 'An error occurred. Please try again.';
                msg.className = 'text-sm mt-2 text-white';
                msg.classList.remove('hidden');
            })
            .finally(() => {
                btn.disabled = false;
            });
        }
    });
</script>
