<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Auto-subscribe for authenticated users
        if ($request->auto_subscribe && auth()->check()) {
            $user = auth()->user();
            
            NewsletterSubscriber::updateOrCreate(
                ['email' => $user->email],
                [
                    'subscriber_type' => get_class($user),
                    'subscriber_id' => $user->id,
                    'name' => $user->name,
                    'is_active' => true,
                    'subscribed_at' => now(),
                ]
            );
            
            return response()->json(['success' => true]);
        }
        
        // Guest subscription
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);
        
        NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
        ]);
        
        return response()->json(['success' => true]);
    }
}
