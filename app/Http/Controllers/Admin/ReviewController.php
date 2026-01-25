<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['reviewer', 'reviewable'])
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('management.portal.admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
