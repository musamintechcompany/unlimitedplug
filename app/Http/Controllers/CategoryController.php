<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $assets = Product::with('prices')
            ->where('category_id', $category->id)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $products = $assets->map(function($asset) use ($currency, $currencySymbol) {
            $price = $asset->getPriceForCurrency($currency);
            $listPrice = $asset->getListPriceForCurrency($currency);
            
            $isFavorited = false;
            if (Auth::check()) {
                $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())
                    ->where('favoritable_type', 'App\\Models\\Product')
                    ->where('favoritable_id', $asset->id)
                    ->exists();
            }
            
            return [
                'id' => $asset->id,
                'title' => $asset->name,
                'description' => $asset->description,
                'image' => $asset->banner ? Storage::url($asset->banner) : 'https://via.placeholder.com/400x300?text=No+Image',
                'price' => (float) $price,
                'currencySymbol' => $currencySymbol,
                'oldPrice' => $listPrice ? (float) $listPrice : null,
                'rating' => 4.5,
                'reviews' => $asset->downloads ?? 0,
                'is_featured' => $asset->is_featured,
                'is_favorited' => $isFavorited,
                'demo_url' => $asset->demo_url,
            ];
        });
        
        return view('categories.index', compact('category', 'products'));
    }

    public function showProduct($slug, $productId)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $asset = Product::where('id', $productId)
            ->where('category_id', $category->id)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();
        
        $price = $asset->getPriceForCurrency($currency);
        $listPrice = $asset->getListPriceForCurrency($currency);
        
        $product = [
            'id' => $asset->id,
            'title' => $asset->name,
            'description' => $asset->description,
            'type' => $asset->type,
            'subcategory' => $asset->subcategory,
            'price' => (float) $price,
            'currency' => $currency,
            'currencySymbol' => $currencySymbol,
            'oldPrice' => $listPrice ? (float) $listPrice : null,
            'rating' => $asset->getAverageRating(),
            'reviews' => $asset->getReviewCount(),
            'image' => $asset->banner ? Storage::url($asset->banner) : 'https://via.placeholder.com/400x300?text=No+Image',
            'media' => $asset->media ? array_map(fn($media) => Storage::url($media), $asset->media) : [],
            'features' => $asset->features ?? [],
            'requirements' => $asset->requirements,
            'demo_url' => $asset->demo_url,
            'badge' => $asset->badge,
            'is_featured' => $asset->is_featured
        ];
        
        $reviews = $asset->approvedReviews()->with('user')->latest()->get()->map(function($review) {
            return [
                'user_name' => $review->user->name,
                'rating' => $review->review_data['rating'] ?? 0,
                'comment' => $review->review_data['comment'] ?? '',
                'created_at' => $review->created_at->format('M d, Y')
            ];
        });
        
        return view('categories.detail', compact('category', 'product', 'reviews'));
    }
}
