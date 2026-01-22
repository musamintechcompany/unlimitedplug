<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FavoriteController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $sessionId = request()->session()->getId();
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');

        $query = Favorite::with('favoritable')
            ->where('favoritable_type', 'App\\Models\\Product');
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        $favorites = $query->latest()->get()->map(function($favorite) use ($currency, $currencySymbol) {
            $product = $favorite->favoritable;
            if (!$product) return null;
            
            $price = $product->getPriceForCurrency($currency);
            $listPrice = $product->getListPriceForCurrency($currency);
            
            return [
                'id' => $product->id,
                'title' => $product->name,
                'price' => (float) $price,
                'currencySymbol' => $currencySymbol,
                'oldPrice' => $listPrice ? (float) $listPrice : null,
                'rating' => $product->getAverageRating(),
                'reviews' => $product->getReviewCount(),
                'image' => $product->banner ? Storage::url($product->banner) : 'https://via.placeholder.com/400x300?text=No+Image',
                'demo_url' => $product->demo_url,
            ];
        })->filter();

        return view('user.favorites', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $productId = $request->product_id;
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $query = Favorite::where('favoritable_type', 'App\\Models\\Product')
                        ->where('favoritable_id', $productId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        $favorite = $query->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['favorited' => false, 'isGuest' => !$userId]);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'favoritable_type' => 'App\\Models\\Product',
                'favoritable_id' => $productId,
                'session_id' => $userId ? null : $sessionId,
            ]);
            return response()->json(['favorited' => true, 'isGuest' => !$userId]);
        }
    }

    public function check(Request $request)
    {
        $productId = $request->product_id;
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $query = Favorite::where('favoritable_type', 'App\\Models\\Product')
                        ->where('favoritable_id', $productId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        $favorite = $query->exists();

        return response()->json(['favorited' => $favorite]);
    }
}