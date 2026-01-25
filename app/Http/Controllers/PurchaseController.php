<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $orders = Order::with(['items.product'])
            ->where('orderable_type', \App\Models\User::class)
            ->where('orderable_id', $user->id)
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.purchases.index', compact('orders'));
    }

    public function show(Product $product)
    {
        $user = Auth::user();
        
        // Verify user owns this product
        $orderItem = OrderItem::whereHas('order', function($q) use ($user) {
                $q->where('orderable_type', \App\Models\User::class)
                  ->where('orderable_id', $user->id)
                  ->where('payment_status', 'completed');
            })
            ->where('product_id', $product->id)
            ->with('order')
            ->first();
        
        if (!$orderItem) {
            abort(403, 'You do not own this product.');
        }
        
        $userReview = $product->reviews()->where('reviewer_id', $user->id)->first();
        
        return view('user.purchases.show', compact('product', 'orderItem', 'userReview'));
    }
}
