<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\DigitalAsset;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $orders = Order::with(['items.digitalAsset'])
            ->where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.purchases.index', compact('orders'));
    }

    public function show(DigitalAsset $digitalAsset)
    {
        $user = Auth::user();
        
        // Verify user owns this asset and get fresh data
        $orderItem = $user->orderItems()
            ->where('digital_asset_id', $digitalAsset->id)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'completed'))
            ->first();
        
        if (!$orderItem) {
            abort(403, 'You do not own this asset.');
        }
        
        // Refresh to get latest download count
        $orderItem->refresh();
        
        return view('user.purchases.show', compact('digitalAsset', 'orderItem'));
    }
}
