<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        
        // Transfer cart items from session to user
        $this->transferCartItems($request, Auth::id());

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
    
    /**
     * Transfer cart items from session to authenticated user
     */
    private function transferCartItems(Request $request, $userId)
    {
        $sessionId = $request->session()->getId();
        
        // Get session cart items
        $sessionCartItems = Cart::where('session_id', $sessionId)->get();
        
        foreach ($sessionCartItems as $sessionItem) {
            // Check if user already has this item in cart
            $existingItem = Cart::where('user_id', $userId)
                ->where('digital_asset_id', $sessionItem->digital_asset_id)
                ->first();
                
            if ($existingItem) {
                // Merge quantities
                $existingItem->increment('quantity', $sessionItem->quantity);
            } else {
                // Transfer item to user
                $sessionItem->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
            }
        }
        
        // Clean up any remaining session cart items
        Cart::where('session_id', $sessionId)->delete();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
