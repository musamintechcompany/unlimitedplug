<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        // Get cart items
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ]);
        }

        // Calculate cart total
        $currency = session('currency', 'USD');
        $cartTotal = $cartItems->sum(function($item) use ($currency) {
            return $item->product->getPriceForCurrency($currency) * $item->quantity;
        });

        // Get product IDs
        $productIds = $cartItems->pluck('product_id')->toArray();

        $currencySymbol = config('payment.currencies.' . $currency . '.symbol', '$');

        // Validate coupon
        $validation = $coupon->isValid($cartTotal, auth()->id(), $productIds, $currencySymbol);
        
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ]);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($cartTotal);
        $newTotal = $cartTotal - $discount;

        // Store coupon in session
        session(['applied_coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
        ]]);

        $currencySymbol = config('payment.currencies.' . $currency . '.symbol', '$');

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'discount' => $discount,
            'newTotal' => $newTotal,
            'currencySymbol' => $currencySymbol,
        ]);
    }

    public function remove()
    {
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed'
        ]);
    }
}
