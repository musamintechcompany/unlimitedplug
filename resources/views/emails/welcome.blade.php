<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .logo-section { text-align: center; padding: 20px 20px 10px; }
        .logo-img { height: 40px; margin-bottom: 5px; }
        .logo-text { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #3b82f6 0%, #000000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .tagline { color: #6b7280; margin: 3px 0 0 0; font-size: 13px; }
        .divider { border: none; height: 3px; background: linear-gradient(90deg, #3b82f6 0%, #000000 100%); margin: 0; }
        .content { padding: 40px 30px; }
        .welcome-text { font-size: 28px; color: #111827; margin: 0 0 15px 0; font-weight: 600; }
        .subtitle { color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0; }
        .products-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 30px 0; }
        .product-card { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; text-decoration: none; }
        .product-image { width: 100%; height: 120px; object-fit: cover; background: #f3f4f6; }
        .product-info { padding: 12px; }
        .product-name { color: #111827; font-size: 14px; font-weight: 600; margin: 0 0 5px 0; }
        .product-price { color: #3b82f6; font-size: 16px; font-weight: bold; margin: 0; }
        .empty-state { text-align: center; padding: 40px 20px; background: #f9fafb; border-radius: 12px; margin: 30px 0; }
        .empty-icon { font-size: 48px; margin-bottom: 15px; }
        .empty-text { color: #6b7280; font-size: 16px; margin: 0 0 10px 0; }
        .button { display: inline-block; color: #3b82f6; text-decoration: none; font-weight: 600; font-size: 16px; }
        .footer { text-align: center; padding: 30px 20px; color: #9ca3af; font-size: 14px; border-top: 1px solid #e5e7eb; }
        .social-links { margin: 15px 0; }
        .social-links a { display: inline-block; margin: 0 8px; color: #6b7280; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <img src="{{ asset('images/logos/logo1.png') }}" alt="UnlimitedPlug" class="logo-img">
            <h1 class="logo-text">UnlimitedPlug</h1>
            <p class="tagline">Your Marketplace for Everything</p>
        </div>
        
        <hr class="divider">
        
        <div class="content">
            <h2 class="welcome-text">Welcome, {{ $user->name }}! 👋</h2>
            <p class="subtitle">
                Thank you for joining UnlimitedPlug! We're excited to have you as part of our growing community. 
                Your account has been successfully created with <strong>{{ $user->email }}</strong>
            </p>
            
            @if($products->count() > 0)
                <h3 style="color: #111827; font-size: 20px; margin: 30px 0 15px 0;">Check Out Our Marketplace</h3>
                <div class="products-grid">
                    @foreach($products as $product)
                    <div class="product-card">
                        @if($product['image'])
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="product-image">
                        @else
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                            </div>
                        @endif
                        <div class="product-info">
                            <p class="product-name">{{ \Illuminate\Support\Str::limit($product['name'], 30) }}</p>
                            <p class="product-price">${{ number_format($product['price'], 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">🎉</div>
                    <p class="empty-text">You're early to the party!</p>
                    <p style="color: #9ca3af; font-size: 14px; margin: 0;">Our marketplace is growing daily. Check back soon for amazing products!</p>
                </div>
            @endif
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/marketplace" class="button">Explore Marketplace</a>
            </div>
            
            <p style="color: #6b7280; font-size: 14px; margin-top: 30px; line-height: 1.6;">
                Need help getting started? Visit our <a href="{{ config('app.url') }}/how-it-works" style="color: #3b82f6;">How It Works</a> page or contact our support team.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0 0 10px 0;">&copy; {{ date('Y') }} UnlimitedPlug. All rights reserved.</p>
            <div class="social-links">
                <a href="#">Twitter</a> • 
                <a href="#">Instagram</a> • 
                <a href="#">Facebook</a>
            </div>
            <p style="font-size: 12px; color: #d1d5db; margin: 15px 0 0 0;">
                You're receiving this email because you created an account at UnlimitedPlug.com
            </p>
        </div>
    </div>
</body>
</html>
