<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index()
    {
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $assets = Product::with(['category', 'prices'])
            ->where('status', 'approved')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $products = $assets->map(function($asset) use ($currency, $currencySymbol) {
            $price = $asset->getPriceForCurrency($currency);
            $listPrice = $asset->getListPriceForCurrency($currency);
            
            // Calculate percentage off
            $percentageOff = null;
            if ($listPrice && $listPrice > $price) {
                $percentageOff = round((($listPrice - $price) / $listPrice) * 100);
            }
            
            // Only show custom badge set by admin (no automatic badges)
            $badge = $asset->badge ? strtoupper($asset->badge) : null;
            
            return [
                'id' => $asset->id,
                'title' => $asset->name,
                'description' => $asset->description,
                'type' => $asset->type,
                'category' => $asset->category ? $asset->category->name : null,
                'subcategory' => $asset->subcategory,
                'price' => (float) $price,
                'currency' => $currency,
                'currencySymbol' => $currencySymbol,
                'oldPrice' => $listPrice ? (float) $listPrice : null,
                'percentageOff' => $percentageOff,
                'rating' => $asset->getAverageRating(),
                'reviews' => $asset->getReviewCount(),
                'image' => $asset->banner ? Storage::url($asset->banner) : ($asset->media && count($asset->media) > 0 ? Storage::url($asset->media[0]) : 'https://via.placeholder.com/400x300?text=No+Image'),
                'badge' => $badge,
                'demo_url' => $asset->demo_url
            ];
        });
            
        return view('marketplace.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $products = Product::where('status', 'approved')
            ->when($query, function($q) use ($query) {
                $q->where(function($subQuery) use ($query) {
                    $subQuery->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('type', 'like', "%{$query}%")
                        ->orWhere('subcategory', 'like', "%{$query}%");
                });
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($asset) use ($currency, $currencySymbol) {
                $price = $asset->getPriceForCurrency($currency);
                $listPrice = $asset->getListPriceForCurrency($currency);
                
                return [
                    'id' => $asset->id,
                    'title' => $asset->name,
                    'description' => $asset->description,
                    'type' => $asset->type,
                    'price' => (float) $price,
                    'currency' => $currency,
                    'currencySymbol' => $currencySymbol,
                    'oldPrice' => $listPrice ? (float) $listPrice : null,
                    'rating' => $asset->getAverageRating(),
                    'reviews' => $asset->getReviewCount(),
                    'image' => $asset->banner ? Storage::url($asset->banner) : ($asset->media && count($asset->media) > 0 ? Storage::url($asset->media[0]) : 'https://via.placeholder.com/400x300?text=No+Image'),
                    'badge' => $asset->badge ? strtoupper($asset->badge) : null
                ];
            });

        return response()->json($products->values());
    }

    public function show($id)
    {
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        $asset = Product::where('status', 'approved')->findOrFail($id);
        
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
            'image' => $asset->banner ? Storage::url($asset->banner) : ($asset->media && count($asset->media) > 0 ? Storage::url($asset->media[0]) : 'https://via.placeholder.com/400x300?text=No+Image'),
            'allImages' => array_merge(
                $asset->banner ? [Storage::url($asset->banner)] : [],
                $asset->media ? array_map(fn($media) => Storage::url($media), $asset->media) : []
            ),
            'features' => $asset->features ?? [],
            'requirements' => $asset->requirements,
            'demo_url' => $asset->demo_url,
            'badge' => $asset->is_featured ? 'FEATURED' : ($asset->list_price && $asset->list_price > $asset->price ? 'SALE' : null)
        ];
        
        $reviews = $asset->approvedReviews()->with('user')->latest()->get()->map(function($review) {
            return [
                'user_name' => $review->user->name,
                'rating' => $review->review_data['rating'] ?? 0,
                'comment' => $review->review_data['comment'] ?? '',
                'created_at' => $review->created_at->format('M d, Y')
            ];
        });
        
        return view('marketplace.product-detail', compact('product', 'reviews'));
    }
}