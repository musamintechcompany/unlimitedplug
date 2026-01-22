<!-- Notification Detail Modal -->
<div id="notification-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" onclick="closeNotificationModal()">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span id="modal-type" class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800"></span>
                    <span id="modal-status" class="ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800"></span>
                </div>
                <button onclick="closeNotificationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <h3 id="modal-title" class="text-2xl font-bold text-gray-900 mb-3"></h3>
            <p id="modal-date" class="text-sm text-gray-500 mb-6"></p>
            <p id="modal-message" class="text-gray-700 text-lg leading-relaxed"></p>
        </div>
    </div>
</div>

<script>
    function openNotificationModal(notification) {
        document.getElementById('modal-type').textContent = notification.type.replace('_', ' ').charAt(0).toUpperCase() + notification.type.replace('_', ' ').slice(1);
        document.getElementById('modal-status').textContent = notification.read_at ? 'Read' : 'Unread';
        document.getElementById('modal-status').className = notification.read_at ? 'ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800' : 'ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800';
        document.getElementById('modal-title').textContent = notification.title;
        document.getElementById('modal-date').textContent = new Date(notification.created_at).toLocaleString();
        document.getElementById('modal-message').textContent = notification.message;
        document.getElementById('notification-modal').classList.remove('hidden');
        
        if (!notification.read_at) {
            markAsRead(notification.id);
        }
    }

    function closeNotificationModal() {
        document.getElementById('notification-modal').classList.add('hidden');
    }
</script>
