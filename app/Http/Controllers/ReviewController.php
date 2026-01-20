<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();
        
        // Check if user purchased the product
        $hasPurchased = $user->orderItems()
            ->where('product_id', $product->id)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'completed'))
            ->exists();
        
        if (!$hasPurchased) {
            return back()->with('error', 'You must purchase this product before reviewing it.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'review_data' => [
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ],
                'is_approved' => false, // Admin approval required
            ]
        );
        
        return back()->with('success', 'Thank you for your review! It will be published after approval.');
    }
}
