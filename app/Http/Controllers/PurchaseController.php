<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $orders = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.purchases.index', compact('orders'));
    }

    public function show(Product $product)
    {
        $user = Auth::user();
        
        // Verify user owns this asset and get fresh data
        $orderItem = $user->orderItems()
            ->with('order')
            ->where('product_id', $product->id)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'completed'))
            ->first();
        
        if (!$orderItem) {
            abort(403, 'You do not own this product.');
        }
        
        // Refresh to get latest download count
        $orderItem->refresh();
        
        // Get user's review if exists
        $userReview = $product->reviews()->where('user_id', $user->id)->first();
        
        return view('user.purchases.show', compact('product', 'orderItem', 'userReview'));
    }
}
