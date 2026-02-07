<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Access Alert - {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .logo-section { text-align: center; padding: 20px 20px 10px; }
        .logo-img { height: 40px; margin-bottom: 0; margin-right: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #3b82f6 0%, #000000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .divider { border: none; height: 3px; background: linear-gradient(90deg, #3b82f6 0%, #000000 100%); margin: 0; }
        .content { padding: 40px 30px; }
        .title { font-size: 28px; color: #111827; margin: 0 0 15px 0; font-weight: 600; }
        .subtitle { color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0; }
        .info-box { background: #f3f4f6; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; }
        .alert { background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; }
        ul { padding-left: 20px; color: #374151; line-height: 1.8; }
        li { margin: 10px 0; }
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
            <h2 class="title">🔐 Account Access Alert</h2>
            <p class="subtitle">Hello <strong>{{ $admin->name }}</strong>,</p>
            
            <p class="subtitle">
                Your admin account was just accessed successfully.
            </p>
            
            <div class="info-box">
                <strong>Login Details:</strong><br>
                Time: {{ $time }}<br>
                IP Address: {{ $ipAddress }}
            </div>
            
            <div class="alert">
                <strong>⚠️ Was this you?</strong><br>
                If you did not log in at this time, please take immediate action:
                <ul style="margin: 10px 0;">
                    <li>Change your password immediately</li>
                    <li>Contact support for assistance</li>
                    <li>Review your account activity</li>
                </ul>
            </div>
            
            <p class="subtitle">
                If this was you, you can safely ignore this email. We send these notifications to help keep your account secure.
            </p>
            
            <p class="subtitle" style="color: #9ca3af; font-size: 14px;">
                Need help? <a href="https://wa.me/{{ config('services.whatsapp.support_number') }}?text={{ urlencode('Hi UnlimitedPlug Support, I need help with my admin account security.') }}" style="color: #3b82f6; text-decoration: none;">Contact our support team</a>.
            </p>
        </div>
        
        @include('emails.partials.footer-admin')
    </div>
</body>
</html>
