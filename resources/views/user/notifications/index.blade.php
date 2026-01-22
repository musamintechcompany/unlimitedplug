<!-- User Notification Sidebar -->
<div id="notification-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white border-l border-gray-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-semibold">Notifications</h2>
            <div class="flex items-center gap-2">
                <button onclick="markAllAsRead()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Mark all read
                </button>
                <button onclick="toggleUserNotifications()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Notification Items -->
        <div id="notification-items" class="flex-1 overflow-y-auto p-4">
            <p class="text-gray-500 text-center">Loading notifications...</p>
        </div>
    </div>
</div>

<!-- Notification Overlay -->
<div id="notification-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleUserNotifications()"></div>

<script>
    function toggleUserNotifications() {
        const sidebar = document.getElementById('notification-sidebar');
        const overlay = document.getElementById('notification-overlay');
        
        if (sidebar.classList.contains('translate-x-full')) {
            sidebar.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            loadUserNotifications();
        } else {
            sidebar.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    function loadUserNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('notification-items');
                if (data.data.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">No notifications yet</p>';
                    return;
                }
                
                container.innerHTML = data.data.map(notification => `
                    <div class="border-b pb-4 mb-4 last:border-b-0 ${notification.read_at ? 'opacity-75' : ''}">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${getTypeColor(notification.type)}">
                                ${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}
                            </span>
                            <div class="flex items-center space-x-2">
                                ${!notification.read_at ? '<span class="w-2 h-2 bg-blue-600 rounded-full"></span>' : ''}
                                <span class="text-xs text-gray-500">${new Date(notification.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">${notification.title}</h4>
                        <p class="text-gray-600 text-sm mb-2">${notification.message}</p>
                        ${!notification.read_at ? `
                            <button onclick="markAsRead('${notification.id}')" class="text-blue-600 hover:text-blue-800 text-sm">
                                Mark as read
                            </button>
                        ` : ''}
                    </div>
                `).join('');
            });
    }

    function getTypeColor(type) {
        const colors = {
            info: 'bg-blue-100 text-blue-800',
            success: 'bg-green-100 text-green-800',
            warning: 'bg-yellow-100 text-yellow-800',
            error: 'bg-red-100 text-red-800'
        };
        return colors[type] || colors.info;
    }

    function markAsRead(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            loadUserNotifications();
            updateNotificationCount();
        });
    }

    function markAllAsRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            loadUserNotifications();
            updateNotificationCount();
        });
    }

    function updateNotificationCount() {
        fetch('/notifications/unread')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('user-notification-count');
                const countElementMobile = document.getElementById('user-notification-count-mobile');
                
                if (data.count > 0) {
                    if (countElement) {
                        countElement.textContent = data.count;
                        countElement.style.display = 'flex';
                    }
                    if (countElementMobile) {
                        countElementMobile.textContent = data.count;
                        countElementMobile.style.display = 'flex';
                    }
                } else {
                    if (countElement) countElement.style.display = 'none';
                    if (countElementMobile) countElementMobile.style.display = 'none';
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateNotificationCount();
    });
</script>