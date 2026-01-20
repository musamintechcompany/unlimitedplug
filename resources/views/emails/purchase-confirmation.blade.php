<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { padding: 30px 20px; text-align: center; border-bottom: 2px solid #e5e7eb; }
        .logo { font-size: 28px; font-weight: bold; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .content { padding: 30px 20px; }
        .order-info { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .item { display: flex; align-items: center; gap: 15px; background: white; padding: 15px; margin: 10px 0; border: 1px solid #e5e7eb; border-radius: 8px; }
        .item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
        .item-placeholder { width: 60px; height: 60px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .item-details { flex: 1; }
        .item-name { font-weight: 600; color: #111827; margin: 0 0 5px 0; }
        .item-price { color: #6b7280; font-size: 14px; margin: 0; }
        .button { display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; font-weight: 600; }
        .license-box { background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="logo">UnlimitedPlug</h1>
        </div>
        
        <div class="content">
            <h2 style="color: #111827; margin-top: 0;">Thank You for Your Purchase!</h2>
            <p>Hi {{ $order->user->name }},</p>
            <p>Your order has been confirmed and is ready for download.</p>
            
            <div class="order-info">
                <p style="margin: 0 0 8px 0;"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p style="margin: 0 0 8px 0;"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p style="margin: 0;"><strong>Total:</strong> {{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($order->total_amount, 2) }}</p>
            </div>
            
            <div class="license-box">
                <p style="margin: 0 0 5px 0; color: #059669; font-weight: bold;">📜 License Information</p>
                <p style="margin: 0; color: #065f46; font-size: 14px;">
                    Your purchase includes a <strong>Regular License</strong>. You can use this for one project.
                    <a href="{{ route('license.terms') }}" style="color: #059669;">View full license terms</a>
                </p>
            </div>
            
            <h3 style="color: #111827;">Your Digital Products</h3>
            @foreach($order->items as $item)
                <div class="item">
                    @if($item->product && $item->product->banner)
                        <img src="{{ asset('storage/' . $item->product->banner) }}" alt="{{ $item->product_name }}" class="item-image">
                    @else
                        <div class="item-placeholder">
                            <svg width="24" height="24" fill="#9ca3af" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            </svg>
                        </div>
                    @endif
                    <div class="item-details">
                        <p class="item-name">{{ $item->product_name }}</p>
                        <p class="item-price">{{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('purchases.index') }}" class="button">Download Your Products</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
                You can download your purchases anytime from your account dashboard.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} UnlimitedPlug. All rights reserved.</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>
