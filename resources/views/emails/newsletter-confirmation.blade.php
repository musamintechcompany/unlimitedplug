<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Newsletter Subscription - {{ config('app.name') }}</title>
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
        .button { display: inline-block; padding: 14px 32px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; margin: 20px 0; }
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
            <h2 class="title">Confirm Your Subscription 📧</h2>
            <p class="subtitle">
                Thanks for signing up for our emails! The fun stuff is coming up. First, just want to make sure we've got the right address.
            </p>
            
            <a href="{{ route('newsletter.confirm', $subscriber->confirmation_token) }}" class="button">Confirm Subscription</a>
            
            <p class="info">
                Click the button above to confirm your email address and start receiving our newsletter.
            </p>
            
            <p class="info" style="color: #9ca3af; font-size: 13px;">
                If you didn't subscribe to our newsletter, you can safely ignore this email.
            </p>
        </div>
        
        @include('emails.partials.footer-marketing', ['unsubscribeUrl' => route('newsletter.unsubscribe', $subscriber->email)])
    </div>
</body>
</html>
