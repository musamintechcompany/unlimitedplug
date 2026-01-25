<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterConfirmation;

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
                    'confirmed_at' => now(),
                    'subscribed_at' => now(),
                ]
            );
            
            return response()->json(['success' => true]);
        }
        
        // Guest subscription
        $request->validate([
            'email' => 'required|email',
        ]);
        
        // Check if already subscribed
        $existing = NewsletterSubscriber::where('email', $request->email)->first();
        
        if ($existing && $existing->is_active) {
            return response()->json(['error' => 'This email is already subscribed.'], 422);
        }
        
        // Generate confirmation token
        $token = Str::random(64);
        
        // Create or update subscriber
        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            [
                'confirmation_token' => $token,
                'is_active' => false,
                'subscriber_type' => null,
                'subscriber_id' => null,
            ]
        );
        
        // Send confirmation email
        Mail::to($subscriber->email)->send(new NewsletterConfirmation($subscriber));
        
        return response()->json(['success' => true]);
    }
    
    public function confirm($token)
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->first();
        
        if (!$subscriber) {
            return view('newsletter.confirm', ['success' => false, 'message' => 'Invalid confirmation link.']);
        }
        
        if ($subscriber->is_active) {
            return view('newsletter.confirm', ['success' => true, 'message' => 'Your email is already confirmed.']);
        }
        
        $subscriber->update([
            'is_active' => true,
            'confirmed_at' => now(),
            'confirmation_token' => null,
        ]);
        
        return view('newsletter.confirm', ['success' => true, 'message' => 'Thank you! Your subscription is confirmed.']);
    }
    
    public function unsubscribe($email)
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        
        if (!$subscriber) {
            return view('newsletter.unsubscribe', ['success' => false, 'message' => 'Email not found.']);
        }
        
        $subscriber->update(['is_active' => false]);
        
        return view('newsletter.unsubscribe', ['success' => true, 'message' => 'You have been unsubscribed.']);
    }
}
