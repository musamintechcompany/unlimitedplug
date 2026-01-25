<section class="bg-blue-600 py-8">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="text-white mb-4 text-sm sm:text-base">Yes! Send me exclusive offers, unique gift ideas, and personalized tips for shopping and selling on UnlimitedPlug.</p>
        <form id="newsletter-form" class="flex flex-col sm:flex-row gap-2 max-w-md mx-auto">
            @csrf
            <input type="email" name="email" required placeholder="Enter your email" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-white focus:border-white w-full">
            <button type="submit" class="bg-white text-blue-600 px-6 py-2 rounded font-semibold hover:bg-gray-100 transition whitespace-nowrap">
                Subscribe
            </button>
        </form>
        <p id="newsletter-msg" class="text-sm mt-2 hidden px-2"></p>
    </div>
</section>

<script>
    document.getElementById('newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const msg = document.getElementById('newsletter-msg');
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
                email: form.email.value
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
                form.reset();
            }
        })
        .catch((error) => {
            msg.textContent = error.message || 'An error occurred. Please try again.';
            msg.className = 'text-sm mt-2 text-white';
            msg.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Subscribe';
        });
    });
</script>
