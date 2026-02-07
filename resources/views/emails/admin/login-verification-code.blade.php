<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Verification Code - {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .logo-section { text-align: center; padding: 20px 20px 10px; }
        .logo-img { height: 40px; margin-bottom: 0; margin-right: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #3b82f6 0%, #000000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .divider { border: none; height: 3px; background: linear-gradient(90deg, #3b82f6 0%, #000000 100%); margin: 0; }
        .content { padding: 40px 30px; text-align: center; }
        .title { font-size: 28px; color: #111827; margin: 0 0 15px 0; font-weight: 600; }
        .subtitle { color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0; }
        .code-box { background: #f3f4f6; border: 2px dashed #3b82f6; border-radius: 8px; padding: 20px; margin: 30px auto; max-width: 300px; }
        .code { font-size: 32px; font-weight: bold; color: #3b82f6; letter-spacing: 8px; margin: 0; font-family: 'Courier New', monospace; }
        .expiry { color: #6b7280; font-size: 14px; margin: 20px 0 0 0; }
        .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                <img src="{{ asset('images/logos/logo1.png') }}" alt="UnlimitedPlug" class="logo-img">
                <h1 class="logo-text">UnlimitedPlug</h1>
            </div>
        </div>
        
        <hr class="divider">
        
        <div class="content">
            <h2 class="title">Login Verification Code 🔐</h2>
            <p class="subtitle">Hello <strong>{{ $admin->name }}</strong>,</p>
            
            <p class="subtitle">
                A login attempt was made to your admin account. Please use the verification code below to complete your login:
            </p>
            
            <div class="code-box">
                <p class="code">{{ $code }}</p>
            </div>
            
            <p class="expiry">⏰ This code will expire in 5 minutes</p>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong> If you did not attempt to log in, please ignore this email and ensure your account password is secure.
            </div>
            
            <p class="subtitle" style="color: #9ca3af; font-size: 14px;">
                Need help? <a href="https://wa.me/{{ config('services.whatsapp.support_number') }}?text={{ urlencode('Hi UnlimitedPlug Support, I need help with admin login.') }}" style="color: #3b82f6; text-decoration: none;">Contact our support team</a>.
            </p>
        </div>
        
        @include('emails.partials.footer-admin')
    </div>
</body>
</html>
