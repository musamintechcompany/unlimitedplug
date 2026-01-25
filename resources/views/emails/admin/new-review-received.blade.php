<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
        .review-box { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #4F46E5; }
        .stars { color: #FFA500; font-size: 18px; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #4F46E5; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Review Received</h2>
        </div>
        
        <div class="content">
            <p><strong>Product:</strong> {{ $review->reviewable->name }}</p>
            <p><strong>Reviewer:</strong> {{ $review->reviewer->name }}</p>
            <p><strong>Email:</strong> {{ $review->reviewer->email }}</p>
            
            <div class="review-box">
                <p><strong>Rating:</strong> 
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $review->review_data['rating'] ? '★' : '☆' }}
                        @endfor
                    </span>
                    ({{ $review->review_data['rating'] }}/5)
                </p>
                
                @if(!empty($review->review_data['comment']))
                    <p><strong>Comment:</strong></p>
                    <p>{{ $review->review_data['comment'] }}</p>
                @endif
                
                @if(!empty($review->review_data['images']))
                    <p><strong>Images:</strong> {{ count($review->review_data['images']) }} photo(s) attached</p>
                @endif
            </div>
            
            <p style="text-align: center;">
                <a href="{{ route('admin.reviews.index') }}" class="btn">Review & Approve</a>
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from Unlimited Plug</p>
        </div>
    </div>
</body>
</html>
