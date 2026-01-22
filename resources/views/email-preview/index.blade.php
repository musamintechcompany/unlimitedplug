<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Templates Preview</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        h1 { color: #111827; margin-bottom: 10px; font-size: 32px; }
        .subtitle { color: #6b7280; margin-bottom: 40px; }
        .section { background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .section-title { color: #374151; font-size: 20px; margin-bottom: 20px; font-weight: 600; }
        .email-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; }
        .email-card { border: 2px solid #e5e7eb; border-radius: 8px; padding: 20px; transition: all 0.2s; cursor: pointer; }
        .email-card:hover { border-color: #3b82f6; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1); transform: translateY(-2px); }
        .email-card h3 { color: #111827; font-size: 16px; margin-bottom: 8px; }
        .email-card p { color: #6b7280; font-size: 14px; }
        .badge { display: inline-block; background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-top: 10px; }
        .badge.admin { background: #fef3c7; color: #92400e; }
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Templates Preview</h1>
        <p class="subtitle">Click any template to preview it in full screen</p>
        
        @if(count($emails['user']) > 0)
        <div class="section">
            <h2 class="section-title">User Emails ({{ count($emails['user']) }})</h2>
            <div class="email-grid">
                @foreach($emails['user'] as $email)
                <a href="{{ route($email['route']) }}" target="_blank">
                    <div class="email-card">
                        <h3>{{ $email['name'] }}</h3>
                        <p>Click to preview</p>
                        <span class="badge">User</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
        @if(count($emails['admin']) > 0)
        <div class="section">
            <h2 class="section-title">Admin Emails ({{ count($emails['admin']) }})</h2>
            <div class="email-grid">
                @foreach($emails['admin'] as $email)
                <a href="{{ route($email['route']) }}" target="_blank">
                    <div class="email-card">
                        <h3>{{ $email['name'] }}</h3>
                        <p>Click to preview</p>
                        <span class="badge admin">Admin</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @else
        <div class="section">
            <h2 class="section-title">Admin Emails (0)</h2>
            <p style="color: #6b7280;">No admin email templates yet. Create them as needed.</p>
        </div>
        @endif
    </div>
</body>
</html>
