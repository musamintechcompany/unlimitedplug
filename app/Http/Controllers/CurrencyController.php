<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function setCurrency(Request $request)
    {
        $currencies = array_keys(config('payment.currencies'));
        
        $request->validate([
            'currency' => 'required|in:' . implode(',', $currencies)
        ]);

        session(['currency' => $request->currency]);

        return response()->json(['success' => true]);
    }
}