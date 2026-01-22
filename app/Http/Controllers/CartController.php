<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        try {
            $request->validate([
                'asset_id' => 'required|uuid|exists:products,id'
            ]);
            
            $assetId = $request->input('asset_id');
            $asset = Product::with('prices')->findOrFail($assetId);
            
            $currency = session('currency', 'USD');
            $price = $asset->getPriceForCurrency($currency);
            
            if (!$price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Price not available for selected currency'
                ], 400);
            }
            
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            // Verify user exists if logged in
            if ($userId && !\App\Models\User::find($userId)) {
                auth()->logout();
                $userId = null;
            }
            
            // Check if item already in cart
            $existingItem = Cart::where('cartable_type', 'App\\Models\\Product')
                ->where('cartable_id', $assetId)
                ->where(function($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->first();
                
            if ($existingItem) {
                $existingItem->increment('quantity');
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'session_id' => $userId ? null : $sessionId,
                    'cartable_type' => 'App\\Models\\Product',
                    'cartable_id' => $assetId,
                    'quantity' => 1,
                    'price' => $price,
                ]);
            }
            
            $cartCount = Cart::getCartCount($userId, $sessionId);
            
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cartCount' => $cartCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding item to cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getCount()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $count = Cart::getCartCount($userId, $sessionId);
        
        return response()->json(['count' => $count]);
    }
    
    public function getItems()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $cartItems = Cart::with('cartable')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
            
        $items = $cartItems->map(function($item) use ($currency, $currencySymbol) {
            $asset = $item->cartable;
            if (!$asset) return null;
            
            $currentPrice = $asset->getPriceForCurrency($currency);
            $image = $asset->banner ? \Storage::url($asset->banner) : ($asset->media && count($asset->media) > 0 ? \Storage::url($asset->media[0]) : 'https://via.placeholder.com/60x60?text=No+Image');
            
            return [
                'id' => $item->id,
                'name' => $asset->name,
                'type' => $asset->type,
                'price' => (float) $currentPrice,
                'quantity' => $item->quantity,
                'currencySymbol' => $currencySymbol,
                'image' => $image,
            ];
        })->filter();
        
        $total = $cartItems->sum(function($item) use ($currency) {
            if (!$item->cartable) return 0;
            $currentPrice = $item->cartable->getPriceForCurrency($currency);
            return $currentPrice * $item->quantity;
        });
        
        return response()->json([
            'items' => $items->values(),
            'total' => number_format($total, 2),
            'currencySymbol' => $currencySymbol
        ]);
    }
    
    public function update(Request $request)
    {
        $cartId = $request->input('cart_id');
        $quantity = $request->input('quantity');
        
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->update(['quantity' => $quantity]);
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        $cartCount = Cart::getCartCount($userId, $sessionId);
        
        return response()->json([
            'success' => true,
            'cartCount' => $cartCount
        ]);
    }
    
    public function remove(Request $request)
    {
        $cartId = $request->input('cart_id');
        
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        $cartCount = Cart::getCartCount($userId, $sessionId);
        
        return response()->json([
            'success' => true,
            'cartCount' => $cartCount
        ]);
    }
    
    public function clear()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'cartCount' => 0
        ]);
    }
    
    /**
     * Merge guest cart to user cart after login/register
     */
    public static function mergeGuestCart($userId, $sessionId)
    {
        // Get guest cart items
        $guestCartItems = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();
        
        if ($guestCartItems->isEmpty()) {
            return;
        }
        
        foreach ($guestCartItems as $guestItem) {
            // Check if user already has this item
            $existingItem = Cart::where('user_id', $userId)
                ->where('cartable_type', $guestItem->cartable_type)
                ->where('cartable_id', $guestItem->cartable_id)
                ->first();
            
            if ($existingItem) {
                // Merge quantities
                $existingItem->increment('quantity', $guestItem->quantity);
                $guestItem->delete();
            } else {
                // Transfer to user
                $guestItem->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
            }
        }
    }
}