<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmed - {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .logo-section { text-align: center; padding: 20px 20px 10px; }
        .logo-img { height: 40px; margin-bottom: 0; }
        .logo-text { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #3b82f6 0%, #000000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .divider { border: none; height: 3px; background: linear-gradient(90deg, #3b82f6 0%, #000000 100%); margin: 0; }
        .content { padding: 40px 30px; }
        .title { font-size: 28px; color: #111827; margin: 0 0 15px 0; font-weight: 600; }
        .subtitle { color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0; }
        .order-box { background: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .order-row { display: flex; justify-content: space-between; margin: 8px 0; }
        .order-label { color: #6b7280; font-size: 14px; }
        .order-value { color: #111827; font-weight: 600; font-size: 14px; }
        .item { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin: 10px 0; display: flex; gap: 15px; align-items: center; }
        .item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
        .item-placeholder { width: 60px; height: 60px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .item-name { font-weight: 600; color: #111827; margin: 0 0 5px 0; font-size: 14px; }
        .item-price { color: #6b7280; font-size: 14px; margin: 0; }
        .license-box { background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .button { display: inline-block; background: #10b981; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div style="display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/logos/logo1.png') }}" alt="UnlimitedPlug" class="logo-img" style="margin-right: 10px;">
                <h1 class="logo-text">UnlimitedPlug</h1>
            </div>
        </div>
        
        <hr class="divider">
        
        <div class="content">
            <h2 class="title">Thank You for Your Purchase! 🎉</h2>
            <p class="subtitle">
                Hi {{ $order->orderable->name ?? 'Customer' }}, your order has been confirmed and is ready for download.
            </p>
            
            <div class="order-box">
                <div class="order-row">
                    <span class="order-label">Order Number:</span>
                    <span class="order-value">{{ $order->order_number }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Date:</span>
                    <span class="order-value">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Total:</span>
                    <span class="order-value">{{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
            
            @php
                $hasLicensedProducts = $order->items->filter(function($item) {
                    return $item->product && isset($item->product->license_type) && $item->product->license_type;
                })->isNotEmpty();
            @endphp
            
            @if($hasLicensedProducts)
            <div class="license-box">
                <p style="margin: 0 0 5px 0; color: #059669; font-weight: bold;">📜 License Information</p>
                <p style="margin: 0; color: #065f46; font-size: 14px;">
                    Some products in your order include licenses. Please review the license terms for each product.
                    <a href="{{ route('license.terms') }}" style="color: #059669; text-decoration: none;">View license terms</a>
                </p>
            </div>
            @endif
            
            <h3 style="color: #111827; font-size: 20px; margin: 30px 0 15px 0;">Your Digital Products</h3>
            @foreach($order->items as $item)
                <div class="item">
                    @if($item->product && $item->product->banner)
                        <img src="{{ asset('storage/' . $item->product->banner) }}" alt="{{ $item->product_name }}" class="item-image">
                    @else
                        <div class="item-placeholder">
                            <svg width="24" height="24" fill="#9ca3af" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" fill="none"/>
                            </svg>
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <p class="item-name">{{ $item->product_name }}</p>
                        <p class="item-price">{{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('purchases.index') }}" class="button">Access Your Products</a>
            </div>
            
            <p style="color: #6b7280; font-size: 14px; margin-top: 30px; line-height: 1.6;">
                You can access your purchases anytime from your account dashboard. Need help? <a href="https://wa.me/{{ config('services.whatsapp.support_number') }}?text={{ urlencode('Hi, I need help with my order: ' . $order->order_number) }}" style="color: #3b82f6; text-decoration: none;">Contact our support team</a>.
            </p>
        </div>
        
        @include('emails.partials.footer')
    </div>
</body>
</html>
