<!-- Admin Notification Sidebar -->
<div id="admin-notification-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white border-l border-gray-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-base sm:text-lg font-semibold">Notifications</h2>
            <div class="flex items-center space-x-2">
                <button onclick="markAllAsRead()" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 whitespace-nowrap">
                    Mark all read
                </button>
                <button onclick="toggleAdminNotifications()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Notification Items -->
        <div id="admin-notification-items" class="flex-1 overflow-y-auto p-4">
            <p class="text-gray-500 text-center py-8">Loading notifications...</p>
        </div>
    </div>
</div>

<!-- Admin Notification Overlay -->
<div id="admin-notification-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleAdminNotifications()"></div>

@include('modals.admin.notification-detail')

<script>
    function toggleAdminNotifications() {
        const sidebar = document.getElementById('admin-notification-sidebar');
        const overlay = document.getElementById('admin-notification-overlay');
        
        if (sidebar.classList.contains('translate-x-full')) {
            sidebar.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            loadAdminNotifications();
        } else {
            sidebar.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    function loadAdminNotifications() {
        fetch('{{ route('admin.notifications.index') }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('admin-notification-items');
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">No notifications</p>';
                    return;
                }
                
                const typeColors = {
                    'user_registered': { 
                        bg: 'bg-green-50', 
                        border: 'border-green-200', 
                        badge: 'bg-green-100 text-green-800', 
                        icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>' 
                    },
                    'order_placed': { 
                        bg: 'bg-blue-50', 
                        border: 'border-blue-200', 
                        badge: 'bg-blue-100 text-blue-800', 
                        icon: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' 
                    },
                    'payment_received': { 
                        bg: 'bg-purple-50', 
                        border: 'border-purple-200', 
                        badge: 'bg-purple-100 text-purple-800', 
                        icon: '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>' 
                    },
                    'product_review': { 
                        bg: 'bg-yellow-50', 
                        border: 'border-yellow-200', 
                        badge: 'bg-yellow-100 text-yellow-800', 
                        icon: '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>' 
                    },
                    'default': { 
                        bg: 'bg-gray-50', 
                        border: 'border-gray-200', 
                        badge: 'bg-gray-100 text-gray-800', 
                        icon: '<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>' 
                    }
                };
                
                container.innerHTML = data.map(notification => {
                    const colors = typeColors[notification.type] || typeColors['default'];
                    return `
                    <div onclick='openNotificationModal(${JSON.stringify(notification)})' class="border ${colors.border} ${colors.bg} pb-4 mb-4 last:border-b-0 cursor-pointer hover:shadow-md p-3 rounded-lg transition">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center space-x-2">
                                <span>${colors.icon}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${colors.badge}">
                                    ${notification.type.replace('_', ' ').charAt(0).toUpperCase() + notification.type.replace('_', ' ').slice(1)}
                                </span>
                                ${!notification.read_at ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">New</span>' : ''}
                            </div>
                            <span class="text-xs text-gray-500">${new Date(notification.created_at).toLocaleDateString()}</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">${notification.title}</h4>
                        <p class="text-gray-600 text-sm line-clamp-2">${notification.message}</p>
                    </div>
                `}).join('');
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('admin-notification-items').innerHTML = '<p class="text-red-500 text-center">Error loading notifications</p>';
            });
    }

    function markAsRead(notificationId) {
        fetch(`/management/portal/admin/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            loadAdminNotifications();
            fetchNotificationCount();
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    function markAllAsRead() {
        fetch('{{ route('admin.notifications.read-all') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            loadAdminNotifications();
            fetchNotificationCount();
        })
        .catch(error => console.error('Error marking all as read:', error));
    }
</script>