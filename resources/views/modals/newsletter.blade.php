<!-- Newsletter Modal for Authenticated Users -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-cloak
     @newsletter-modal.window="show = true"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="show = false"></div>
    
    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="show = false">
            
            <!-- Close Button -->
            <button @click="show = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Content -->
            <div class="text-center mb-6">
                <div class="text-5xl mb-3">📬</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Stay Updated!</h3>
                <p class="text-gray-600">Get notified about new products and exclusive deals.</p>
            </div>
            
            <form id="newsletter-modal-form" class="space-y-4">
                @csrf
                <input type="hidden" name="auto_subscribe" value="1">
                
                <button type="submit" id="modal-submit-btn"
                        class="w-full bg-green-600 text-white py-3 rounded-md font-semibold hover:bg-green-700 transition">
                    Yes, Subscribe Me!
                </button>
                
                <button type="button" @click="show = false"
                        class="w-full bg-gray-200 text-gray-700 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
                    Maybe Later
                </button>
            </form>
            
            <div id="modal-success" class="hidden text-center">
                <div class="text-5xl mb-3">🎉</div>
                <p class="text-green-600 font-semibold mb-4">Successfully subscribed!</p>
                <button @click="show = false" class="text-blue-600 hover:underline">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('newsletter-modal-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('modal-submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Subscribing...';
        
        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ auto_subscribe: true })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('newsletter-modal-form').classList.add('hidden');
                document.getElementById('modal-success').classList.remove('hidden');
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Yes, Subscribe Me!';
        });
    });
</script>
