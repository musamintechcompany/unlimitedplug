<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
        $this->delay(now()->addSeconds(10));
        
        $this->afterCommit();
    }

    public function build()
    {
        $products = \App\Models\Product::latest()->take(4)->get()->map(function($product) {
            return [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->banner ? \Storage::url($product->banner) : null
            ];
        });
        
        return $this->subject('Welcome to ' . config('app.name'))
                    ->view('emails.welcome')
                    ->with(['user' => $this->user, 'products' => $products]);
    }
}