<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get total purchases count
        $totalPurchases = Order::where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->count();
        
        // Get recent purchases (last 3)
        $recentPurchases = Order::with(['items.digitalAsset'])
            ->where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('user.dashboard', compact('totalPurchases', 'recentPurchases'));
    }
}
