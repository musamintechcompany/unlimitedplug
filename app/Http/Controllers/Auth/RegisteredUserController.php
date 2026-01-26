<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Admin;
use App\Models\Notification;
use App\Events\AdminNotificationCreated;
use App\Events\AnalyticsUpdated;
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
            'terms' => ['accepted'],
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
        
        // Notify all admins about new user registration
        $this->notifyAdmins($user);
        
        // Transfer cart items from old session to user
        $this->transferCartItems($oldSessionId, $user->id);
        
        // Transfer favorites from old session to user
        $this->transferFavorites($oldSessionId, $user->id);

        return redirect(route('dashboard', absolute: false))->with('show_newsletter_modal', true);
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
                ->where('cartable_type', $sessionItem->cartable_type)
                ->where('cartable_id', $sessionItem->cartable_id)
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
                ->where('favoritable_type', $sessionFavorite->favoritable_type)
                ->where('favoritable_id', $sessionFavorite->favoritable_id)
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
     * Notify all admins about new user registration
     */
    private function notifyAdmins($user)
    {
        $admins = Admin::all();
        
        foreach ($admins as $admin) {
            $notification = Notification::create([
                'notifiable_type' => Admin::class,
                'notifiable_id' => $admin->id,
                'type' => 'user_registered',
                'title' => 'New User Registration',
                'message' => $user->name . ' just registered on the platform.',
                'data' => json_encode(['user_id' => $user->id, 'user_email' => $user->email])
            ]);
            
            broadcast(new AdminNotificationCreated($notification));
        }
        
        broadcast(new AnalyticsUpdated());
    }
}
