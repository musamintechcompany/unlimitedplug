<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Change Verification - {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .logo-section { text-align: center; padding: 20px 20px 10px; }
        .logo-img { height: 40px; margin-bottom: 0; margin-right: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #3b82f6 0%, #000000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; display: inline-block; }
        .divider { border: none; height: 3px; background: linear-gradient(90deg, #3b82f6 0%, #000000 100%); margin: 0; }
        .content { padding: 40px 30px; text-align: center; }
        .title { font-size: 28px; color: #111827; margin: 0 0 15px 0; font-weight: 600; }
        .subtitle { color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0; }
        .code-box { background: #f3f4f6; border: 2px dashed #3b82f6; border-radius: 8px; padding: 20px; margin: 30px auto; max-width: 300px; }
        .code { font-size: 32px; font-weight: bold; color: #3b82f6; letter-spacing: 4px; margin: 0; font-family: 'Courier New', monospace; word-break: break-all; }
        .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; text-align: left; }
        @media only screen and (max-width: 600px) {
            .code { font-size: 28px; letter-spacing: 3px; }
            .code-box { padding: 15px; max-width: 250px; }
        }
        @media only screen and (max-width: 400px) {
            .code { font-size: 24px; letter-spacing: 2px; }
            .code-box { max-width: 200px; }
        }
        .expiry { color: #6b7280; font-size: 14px; margin: 20px 0 0 0; }
        .info { color: #6b7280; font-size: 14px; line-height: 1.6; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div style="display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/logos/logo1.png') }}" alt="UnlimitedPlug" class="logo-img">
                <h1 class="logo-text">UnlimitedPlug</h1>
            </div>
        </div>
        
        <hr class="divider">
        
        <div class="content">
            <h2 class="title">Email Change Request 🔐</h2>
            <p class="subtitle">
                An email change was requested for your UnlimitedPlug account to this email address.
            </p>
            
            <p class="subtitle">
                If this was you, please use the verification code below to confirm the change:
            </p>
            
            <div class="code-box">
                <p class="code">{{ $code }}</p>
            </div>
            
            <p class="expiry">⏰ This code expires in 10 minutes</p>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong> If you did not request this email change, please ignore this email and your account email will remain unchanged. Consider changing your password if you suspect unauthorized access.
            </div>
            
            <p class="info" style="color: #9ca3af; font-size: 13px;">
                Need help? <a href="https://wa.me/{{ config('services.whatsapp.support_number') }}?text={{ urlencode('Hi UnlimitedPlug Support, I need help with email change.') }}" style="color: #3b82f6; text-decoration: none;">Contact our support team</a>.
            </p>
        </div>
        
        @include('emails.partials.footer')
    </div>
</body>
</html>
