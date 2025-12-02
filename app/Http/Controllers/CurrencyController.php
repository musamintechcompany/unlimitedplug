<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:USD,NGN'
        ]);

        session(['currency' => $request->currency]);

        return response()->json(['success' => true]);
    }
}