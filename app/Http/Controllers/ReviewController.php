<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Events\AdminNotificationCreated;
use App\Mail\Admin\NewReviewReceived;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();
        
        // Check if user purchased the product
        $hasPurchased = \App\Models\OrderItem::whereHas('order', function($q) use ($user) {
                $q->where('orderable_type', \App\Models\User::class)
                  ->where('orderable_id', $user->id)
                  ->where('payment_status', 'completed');
            })
            ->where('product_id', $product->id)
            ->exists();
        
        if (!$hasPurchased) {
            return back()->with('error', 'You must purchase this product before reviewing it.');
        }
        
        // Check if review exists and if it's within 3 minutes
        $existingReview = Review::where('reviewer_type', get_class($user))
            ->where('reviewer_id', $user->id)
            ->where('reviewable_type', get_class($product))
            ->where('reviewable_id', $product->id)
            ->first();
        
        if ($existingReview && $existingReview->created_at->diffInMinutes(now()) > 3) {
            return back()->with('error', 'You can only edit your review within 3 minutes of posting.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048'
        ]);
        
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('reviews', 'public');
            }
        }
        
        $reviewData = [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];
        
        if (!empty($images)) {
            $reviewData['images'] = $images;
        }
        
        if ($existingReview) {
            $existingReview->update([
                'review_data' => $reviewData,
                'is_approved' => false,
            ]);
            $review = $existingReview;
        } else {
            $review = Review::create([
                'reviewer_type' => get_class($user),
                'reviewer_id' => $user->id,
                'reviewable_type' => get_class($product),
                'reviewable_id' => $product->id,
                'review_data' => $reviewData,
                'is_approved' => false,
            ]);
            
            // Notify admins about new review
            $this->notifyAdmins($review);
        }
        
        return redirect()->route('purchases.show', $product->id)->with('success', 'Thank you for your review! It will be published after approval.');
    }
    
    private function notifyAdmins($review)
    {
        $admins = Admin::all();
        $review->load(['reviewer', 'reviewable']);
        
        foreach ($admins as $admin) {
            $notification = Notification::create([
                'notifiable_type' => Admin::class,
                'notifiable_id' => $admin->id,
                'type' => 'review_submitted',
                'title' => 'New Review Received',
                'message' => $review->reviewer->name . ' reviewed "' . $review->reviewable->name . '" (' . $review->review_data['rating'] . ' stars)',
                'data' => json_encode(['review_id' => $review->id, 'product_id' => $review->reviewable->id])
            ]);
            
            broadcast(new AdminNotificationCreated($notification));
        }
        
        // Send email notification to admin
        $adminEmail = env('ADMIN_EMAIL');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewReviewReceived($review));
            } catch (\Exception $e) {
                \Log::error('Failed to send admin review notification email: ' . $e->getMessage());
            }
        }
    }
}
