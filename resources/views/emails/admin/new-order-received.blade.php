<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Order Received - {{ config('app.name') }}</title>
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
        .alert-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .info-box { background: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; margin: 8px 0; }
        .info-label { color: #6b7280; font-size: 14px; }
        .info-value { color: #111827; font-weight: 600; font-size: 14px; }
        .item { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin: 10px 0; display: flex; gap: 15px; align-items: center; }
        .item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
        .item-placeholder { width: 60px; height: 60px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .item-name { font-weight: 600; color: #111827; margin: 0 0 5px 0; font-size: 14px; }
        .item-price { color: #6b7280; font-size: 14px; margin: 0; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; }
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
            <h2 class="title">🎉 New Order Received!</h2>
            <p class="subtitle">
                A new order has been placed and payment confirmed.
            </p>
            
            <h3 style="color: #111827; font-size: 20px; margin: 30px 0 15px 0;">Order Details</h3>
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Amount:</span>
                    <span class="info-value">{{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Transaction Ref:</span>
                    <span class="info-value">{{ $order->transaction_reference }}</span>
                </div>
            </div>
            
            <h3 style="color: #111827; font-size: 20px; margin: 30px 0 15px 0;">Customer Information</h3>
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $order->orderable->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->orderable->email ?? 'N/A' }}</span>
                </div>
            </div>
            
            <h3 style="color: #111827; font-size: 20px; margin: 30px 0 15px 0;">Products Ordered</h3>
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
                        <p class="item-price">Qty: {{ $item->quantity }} × {{ config('payment.currencies.' . $order->currency . '.symbol') }}{{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/admin/dashboard" class="button">View in Admin Panel</a>
            </div>
        </div>
        
        @include('emails.partials.footer')
    </div>
</body>
</html>
