<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class EmailPreviewController extends Controller
{
    public function index()
    {
        $emails = [
            'user' => [
                ['name' => 'Welcome Email', 'route' => 'email.preview.welcome'],
                ['name' => 'Email Verification Code', 'route' => 'email.preview.verification'],
                ['name' => 'Password Reset Code', 'route' => 'email.preview.password-reset'],
                ['name' => 'Purchase Confirmation', 'route' => 'email.preview.purchase'],
            ],
            'admin' => [
                // Add admin emails here when created
            ]
        ];
        
        return view('email-preview.index', compact('emails'));
    }
    
    public function welcome()
    {
        $user = User::first() ?? (object)[
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        // Get 4 featured products for email
        $products = \App\Models\Product::where('status', 'approved')
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->take(4)
            ->get()
            ->map(function($product) {
                return [
                    'name' => $product->name,
                    'price' => $product->getPriceForCurrency('USD'),
                    'image' => $product->banner ? asset('storage/' . $product->banner) : null,
                ];
            });
        
        return view('emails.welcome', compact('user', 'products'));
    }
    
    public function verification()
    {
        $code = '123456';
        return view('emails.verification-code', compact('code'));
    }
    
    public function passwordReset()
    {
        $code = '789012';
        return view('emails.password-reset-code', compact('code'));
    }
    
    public function purchase()
    {
        $order = Order::with(['user', 'items.product'])->first();
        
        if (!$order) {
            // Create dummy data for preview
            $order = (object)[
                'order_number' => 'ORD-2024-001',
                'created_at' => now(),
                'total_amount' => 99.99,
                'currency' => 'USD',
                'user' => (object)['name' => 'John Doe'],
                'items' => collect([
                    (object)[
                        'product_name' => 'Premium WordPress Theme',
                        'price' => 49.99,
                        'product' => (object)['banner' => null]
                    ],
                    (object)[
                        'product_name' => 'Logo Design Pack',
                        'price' => 50.00,
                        'product' => (object)['banner' => null]
                    ]
                ])
            ];
        }
        
        return view('emails.purchase-confirmation', compact('order'));
    }
}
