<div class="footer" style="text-align: center; padding: 30px 20px; color: #9ca3af; font-size: 14px; border-top: 1px solid #e5e7eb;">
    <p style="margin: 0 0 10px 0;">&copy; {{ date('Y') }} UnlimitedPlug. All rights reserved.</p>
    
    <div class="social-links" style="margin: 15px 0;">
        <a href="#" style="display: inline-block; margin: 0 8px; color: #6b7280; text-decoration: none;">Twitter</a> • 
        <a href="#" style="display: inline-block; margin: 0 8px; color: #6b7280; text-decoration: none;">Instagram</a> • 
        <a href="#" style="display: inline-block; margin: 0 8px; color: #6b7280; text-decoration: none;">Facebook</a>
    </div>
    
    <div style="margin: 15px 0;">
        <a href="{{ config('app.url') }}/policy" style="color: #6b7280; text-decoration: none; margin: 0 8px;">Privacy Policy</a> • 
        <a href="{{ config('app.url') }}/terms" style="color: #6b7280; text-decoration: none; margin: 0 8px;">Terms</a>
    </div>
    
    <p style="font-size: 12px; color: #d1d5db; margin: 15px 0 0 0;">
        You're receiving this email because you subscribed to our newsletter.
    </p>
    
    <p style="font-size: 12px; margin: 10px 0 0 0;">
        <a href="{{ $unsubscribeUrl ?? '#' }}" style="color: #9ca3af; text-decoration: underline;">Unsubscribe</a>
    </p>
</div>
