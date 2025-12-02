<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for joining {{ config('app.name') }}. We're excited to have you on board!</p>
    <p>Your account has been successfully created with the email: <strong>{{ $user->email }}</strong></p>
    <p>If you have any questions, feel free to reach out to our support team.</p>
    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
</body>
</html>