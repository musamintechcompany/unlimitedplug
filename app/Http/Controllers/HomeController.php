<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalAsset;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $currency = session('currency', 'USD');
        $currencySymbol = config('payment.currencies.' . $currency . '.symbol');
        
        // Group products by type, get latest 4 per type
        $types = ['website', 'template', 'plugin', 'service', 'digital'];
        $categorizedProducts = [];
        
        foreach ($types as $type) {
            $assets = DigitalAsset::with('prices')
                ->where('status', 'approved')
                ->where('type', $type)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            
            if ($assets->isNotEmpty()) {
                $categorizedProducts[$type] = $assets->map(function($asset) use ($currency, $currencySymbol) {
                    $price = $asset->getPriceForCurrency($currency);
                    $listPrice = $asset->getListPriceForCurrency($currency);
                    
                    return [
                        'id' => $asset->id,
                        'title' => $asset->name,
                        'image' => $asset->banner ? Storage::url($asset->banner) : 'https://via.placeholder.com/400x300?text=No+Image',
                        'price' => (float) $price,
                        'currencySymbol' => $currencySymbol,
                        'oldPrice' => $listPrice ? (float) $listPrice : null,
                    ];
                });
            }
        }
            
        return view('welcome', compact('categorizedProducts'));
    }
}
