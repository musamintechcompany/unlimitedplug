<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; }
        .item { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .button { display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Your Purchase!</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $order->user->name }},</p>
            <p>Your order has been confirmed and is ready for download.</p>
            
            <div style="background: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #1976D2; font-weight: bold;">📜 License Information</p>
                <p style="margin: 5px 0 0 0; color: #555; font-size: 14px;">
                    Your purchase includes a <strong>Regular License</strong>. You can use this for one project.
                    <a href="{{ route('license.terms') }}" style="color: #2196F3;">View full license terms</a>
                </p>
            </div>
            
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
            <p><strong>Total:</strong> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            
            <h3>Your Digital Products</h3>
            @foreach($order->items as $item)
                <div class="item">
                    <h4>{{ $item->asset_name }}</h4>
                    <p>Price: {{ $order->currency }} {{ number_format($item->price, 2) }}</p>
                    <a href="{{ route('download', $item->id) }}" class="button">Download Now</a>
                </div>
            @endforeach
            
            <p style="margin-top: 20px;">
                <a href="{{ route('purchases.index') }}" class="button">View All My Purchases</a>
            </p>
            
            <p style="margin-top: 20px; font-size: 14px; color: #666;">
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
