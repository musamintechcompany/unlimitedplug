<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome Super Admin - {{ config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .logo-section { text-align: center; padding: 20px 20px 10px; }
        .logo-img { height: 40px; margin-bottom: 0; margin-right: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #3b82f6 0%, #000000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .divider { border: none; height: 3px; background: linear-gradient(90deg, #3b82f6 0%, #000000 100%); margin: 0; }
        .content { padding: 40px 30px; }
        .title { font-size: 28px; color: #111827; margin: 0 0 15px 0; font-weight: 600; }
        .badge { background: #10b981; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; margin-bottom: 20px; }
        .subtitle { color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0; }
        ul { padding-left: 20px; color: #374151; line-height: 1.8; }
        li { margin: 10px 0; }
        .info-box { background: #f3f4f6; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; }
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
            <h2 class="title">Welcome, Super Admin! 🎉</h2>
            <span class="badge">SUPER ADMIN</span>
            
            <p class="subtitle">Hello <strong>{{ $admin->name }}</strong>,</p>
            
            <p class="subtitle">
                Congratulations! You are now the <strong>Super Admin</strong> of UnlimitedPlug.
            </p>
            
            <p class="subtitle">As the Super Admin, you have full control over:</p>
            <ul>
                <li>All administrative functions and settings</li>
                <li>User and admin management</li>
                <li>Complete access to all data and operations</li>
                <li>Authority over other administrators</li>
            </ul>
            
            <div class="info-box">
                <strong>Your Account Details:</strong><br>
                Email: {{ $admin->email }}<br>
                Role: Super Administrator
            </div>
            
            <p class="subtitle">
                For security, you'll receive a verification code each time you log in.
            </p>
            
            <p class="subtitle" style="color: #9ca3af; font-size: 14px;">
                If you have any questions, please <a href="https://wa.me/{{ config('services.whatsapp.support_number') }}?text={{ urlencode('Hi UnlimitedPlug Support, I need help with admin account.') }}" style="color: #3b82f6; text-decoration: none;">contact support</a>.
            </p>
        </div>
        
        @include('emails.partials.footer-admin')
    </div>
</body>
</html>
