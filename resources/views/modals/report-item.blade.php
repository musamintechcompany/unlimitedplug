<!-- Report Item Modal -->
<div id="reportItemModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Report this item</h3>
            <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @guest
        <!-- Not Authenticated -->
        <div class="text-center py-6">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-900 mb-2">Please login to report item</p>
            <p class="text-sm text-gray-600 mb-6">You need to be logged in to report items</p>
            <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                Login
            </a>
        </div>
        @else
        <!-- Authenticated - Show Form -->
        <form id="reportForm" onsubmit="submitReport(event)">
            @csrf
            <input type="hidden" name="reportable_type" value="App\Models\Product">
            <input type="hidden" name="reportable_id" id="reportable_id" value="">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for reporting</label>
                <select name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a reason</option>
                    <option value="copyright">Copyright infringement</option>
                    <option value="inappropriate">Inappropriate content</option>
                    <option value="misleading">Misleading description</option>
                    <option value="broken">Broken or damaged file</option>
                    <option value="spam">Spam or scam</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional details</label>
                <textarea name="details" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Please provide more information about your report..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeReportModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                    Submit Report
                </button>
            </div>
        </form>
        @endguest
    </div>
</div>

<script>
function openReportModal(productId) {
    @auth
    document.getElementById('reportable_id').value = productId;
    @endauth
    const modal = document.getElementById('reportItemModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeReportModal() {
    const modal = document.getElementById('reportItemModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
    @auth
    document.getElementById('reportForm').reset();
    @endauth
}

function submitReport(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch('{{ route("reports.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeReportModal();
            showNotification(data.message, 'success');
        } else {
            showNotification('Error submitting report', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error submitting report', 'error');
    });
}

// Close modal when clicking outside
document.getElementById('reportItemModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReportModal();
    }
});
</script>
