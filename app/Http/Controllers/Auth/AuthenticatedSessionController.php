<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Models\Favorite;
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
        // Capture session ID BEFORE authentication
        $oldSessionId = $request->session()->getId();
        
        $request->authenticate();
        
        // Transfer cart items from old session to user
        $this->transferCartItems($oldSessionId, Auth::id());
        
        // Transfer favorites from old session to user
        $this->transferFavorites($oldSessionId, Auth::id());

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
    
    /**
     * Transfer cart items from session to authenticated user
     */
    private function transferCartItems($sessionId, $userId)
    {
        // Get session cart items
        $sessionCartItems = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();
        
        foreach ($sessionCartItems as $sessionItem) {
            // Check if user already has this item in cart
            $existingItem = Cart::where('user_id', $userId)
                ->where('product_id', $sessionItem->product_id)
                ->first();
                
            if ($existingItem) {
                // Merge quantities
                $existingItem->increment('quantity', $sessionItem->quantity);
                $sessionItem->delete();
            } else {
                // Transfer item to user
                $sessionItem->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
            }
        }
    }
    
    /**
     * Transfer favorites from session to authenticated user
     */
    private function transferFavorites($sessionId, $userId)
    {
        // Get session favorites
        $sessionFavorites = Favorite::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();
        
        foreach ($sessionFavorites as $sessionFavorite) {
            // Check if user already has this favorite
            $existingFavorite = Favorite::where('user_id', $userId)
                ->where('product_id', $sessionFavorite->product_id)
                ->first();
                
            if ($existingFavorite) {
                // Delete duplicate
                $sessionFavorite->delete();
            } else {
                // Transfer favorite to user
                $sessionFavorite->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
            }
        }
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
