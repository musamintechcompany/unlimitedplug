<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\WelcomeEmail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Capture session ID BEFORE creating user
        $oldSessionId = $request->session()->getId();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
        
        // Transfer cart items from old session to user
        $this->transferCartItems($oldSessionId, $user->id);

        return redirect(route('dashboard', absolute: false));
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
                ->where('digital_asset_id', $sessionItem->digital_asset_id)
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
}
