# Real-Time Features Setup with Laravel Reverb

## What's Implemented

### 1. Real-Time Notifications
- Bell icon count updates instantly when new notifications arrive
- Notification sidebar refreshes automatically if open
- Works for: User registrations, Order placements

### 2. Real-Time Analytics Dashboard
- Line chart updates automatically when new users register or orders are placed
- No need to refresh the page to see latest data

## How to Start Reverb Server

### Option 1: Using Artisan Command
```bash
php artisan reverb:start
```

### Option 2: Using Artisan with Debug Mode
```bash
php artisan reverb:start --debug
```

### Option 3: Run in Background (Production)
```bash
php artisan reverb:start > /dev/null 2>&1 &
```

## Testing Real-Time Features

### Test Notifications:
1. Open admin dashboard in one browser tab
2. Register a new user in another tab (or incognito window)
3. Watch the bell icon count update instantly in admin dashboard
4. Click bell to see the new notification appear

### Test Analytics:
1. Open admin dashboard
2. Keep the dashboard open
3. Register a new user or place an order
4. Watch the line chart update automatically

## Troubleshooting

### If real-time updates don't work:

1. **Check Reverb is running:**
   ```bash
   php artisan reverb:start --debug
   ```
   You should see: "Reverb server started on..."

2. **Check browser console:**
   - Open DevTools (F12)
   - Look for WebSocket connection messages
   - Should see: "New notification received:" when events fire

3. **Verify .env settings:**
   ```
   BROADCAST_CONNECTION=reverb
   REVERB_APP_ID=147791
   REVERB_APP_KEY=kxaibpu78ixpk3sjmd5f
   REVERB_APP_SECRET=l1h9nknqyxmdk3ccyia5
   REVERB_HOST=localhost
   REVERB_PORT=8080
   REVERB_SCHEME=http
   ```

4. **Check Redis is running:**
   Reverb uses Redis for queue management
   ```bash
   redis-cli ping
   ```
   Should return: PONG

## Files Modified

### Backend:
- `app/Events/AdminNotificationCreated.php` - Broadcasts new notifications
- `app/Events/AnalyticsUpdated.php` - Broadcasts analytics updates
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Triggers events on user registration
- `app/Http/Controllers/PaystackController.php` - Triggers events on order placement

### Frontend:
- `resources/views/layouts/admin/app.blade.php` - Added Echo listener setup
- Automatically listens to `admin-notifications` and `admin-analytics` channels

## How It Works

1. **User registers** → `AdminNotificationCreated` event fires → All admin dashboards receive notification instantly
2. **Order placed** → Both `AdminNotificationCreated` and `AnalyticsUpdated` events fire → Notifications + chart update
3. **WebSocket connection** → Reverb server pushes updates to all connected admin browsers in real-time

## Production Deployment

For production, consider:
1. Use a process manager like Supervisor to keep Reverb running
2. Set `REVERB_SCHEME=https` for SSL
3. Configure proper firewall rules for WebSocket port (8080)
