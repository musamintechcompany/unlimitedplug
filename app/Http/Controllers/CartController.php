<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DigitalAsset;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $assetId = $request->input('asset_id');
        $asset = DigitalAsset::findOrFail($assetId);
        
        $currency = session('currency', 'NGN');
        $price = $asset->getPriceForCurrency($currency);
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        // Check if item already in cart
        $existingItem = Cart::where('digital_asset_id', $assetId)
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
                'digital_asset_id' => $assetId,
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
        $currency = session('currency', 'NGN');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $cartItems = Cart::with('digitalAsset')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
            
        $items = $cartItems->map(function($item) use ($currency, $currencySymbol) {
            $currentPrice = $item->digitalAsset->getPriceForCurrency($currency);
            $asset = $item->digitalAsset;
            $image = $asset->banner ? \Storage::url($asset->banner) : ($asset->media && count($asset->media) > 0 ? \Storage::url($asset->media[0]) : 'https://via.placeholder.com/60x60?text=No+Image');
            
            return [
                'id' => $item->id,
                'name' => $asset->name,
                'type' => $asset->type,
                'price' => number_format($currentPrice, 2),
                'quantity' => $item->quantity,
                'currencySymbol' => $currencySymbol,
                'image' => $image,
            ];
        });
        
        $total = $cartItems->sum(function($item) use ($currency) {
            $currentPrice = $item->digitalAsset->getPriceForCurrency($currency);
            return $currentPrice * $item->quantity;
        });
        
        return response()->json([
            'items' => $items,
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
}