<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function success()
    {
        // Check if user came from payment callback
        if (!session()->has('payment_success')) {
            return redirect()->route('marketplace')->with('info', 'No recent payment found');
        }

        $order = Order::with(['items.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        // Clear the session flag
        session()->forget('payment_success');

        return view('user.checkout-success', compact('order'));
    }
}
