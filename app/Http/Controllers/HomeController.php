<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        // Group products by category, get latest 4 per category
        $categorizedProducts = Product::with(['prices', 'category'])
            ->where('status', 'approved')
            ->whereNotNull('category_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category.name')
            ->map(function($products) use ($currency, $currencySymbol) {
                return $products->take(4)->map(function($asset) use ($currency, $currencySymbol) {
                    $price = $asset->getPriceForCurrency($currency);
                    $listPrice = $asset->getListPriceForCurrency($currency);
                    
                    return [
                        'id' => $asset->id,
                        'title' => $asset->name,
                        'image' => $asset->banner ? Storage::url($asset->banner) : ($asset->media && count($asset->media) > 0 ? Storage::url($asset->media[0]) : 'https://via.placeholder.com/200?text=No+Image'),
                        'price' => (float) $price,
                        'currencySymbol' => $currencySymbol,
                        'oldPrice' => $listPrice ? (float) $listPrice : null,
                        'subcategory' => $asset->subcategory,
                    ];
                });
            });
            
        return view('welcome', compact('categorizedProducts'));
    }
}
