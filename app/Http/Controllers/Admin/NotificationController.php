<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        if (request()->expectsJson()) {
            $notifications = auth('admin')->user()->notifications()->latest()->get();
            return response()->json($notifications);
        }
        
        $notifications = auth('admin')->user()->notifications()->latest()->paginate(20);
        return view('management.portal.admin.notifications.index', compact('notifications'));
    }

    public function count()
    {
        $count = auth('admin')->user()->notifications()->whereNull('read_at')->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = auth('admin')->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth('admin')->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
