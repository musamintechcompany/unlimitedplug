<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DigitalAsset extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'category_id',
        'subcategory',
        'price',
        'list_price',
        'banner',
        'media',
        'file',
        'demo_url',
        'tags',
        'features',
        'requirements',
        'status',
        'is_featured',
        'badge',
    ];

    protected $casts = [
        'media' => 'array',
        'tags' => 'array',
        'features' => 'array',
        'file' => 'array',
        'price' => 'decimal:2',
        'list_price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(AssetPrice::class, 'asset_id');
    }

    public function getPriceForCurrency($currencyCode)
    {
        // First check if specific currency price exists
        $price = $this->prices()->where('currency_code', $currencyCode)->first();
        
        if ($price && $price->price !== null) {
            return $price->price;
        }
        
        $exchangeRate = config('payment.exchange_rate', 1500);
        
        // If requesting NGN but no NGN price, convert USD to NGN
        if ($currencyCode === 'NGN') {
            $usdPrice = $this->prices()->where('currency_code', 'USD')->first();
            if ($usdPrice && $usdPrice->price !== null) {
                return round($usdPrice->price * $exchangeRate, 2);
            }
            // Fallback: convert default price (assume USD) to NGN
            if ($this->price) {
                return round($this->price * $exchangeRate, 2);
            }
        }
        
        // If requesting USD but no USD price, convert NGN to USD
        if ($currencyCode === 'USD') {
            $ngnPrice = $this->prices()->where('currency_code', 'NGN')->first();
            if ($ngnPrice && $ngnPrice->price !== null) {
                return round($ngnPrice->price / $exchangeRate, 2);
            }
        }
        
        return $this->price; // Final fallback to default price
    }
    
    public function getListPriceForCurrency($currencyCode)
    {
        // First check if specific currency list_price exists
        $price = $this->prices()->where('currency_code', $currencyCode)->first();
        
        if ($price && $price->list_price !== null) {
            return $price->list_price;
        }
        
        $listExchangeRate = config('payment.list_exchange_rate', 1500);
        
        // If requesting NGN but no NGN list_price, convert USD to NGN
        if ($currencyCode === 'NGN') {
            $usdPrice = $this->prices()->where('currency_code', 'USD')->first();
            if ($usdPrice && $usdPrice->list_price !== null) {
                return round($usdPrice->list_price * $listExchangeRate, 2);
            }
            // Fallback: convert default list_price (assume USD) to NGN
            if ($this->list_price) {
                return round($this->list_price * $listExchangeRate, 2);
            }
        }
        
        // If requesting USD but no USD list_price, convert NGN to USD
        if ($currencyCode === 'USD') {
            $ngnPrice = $this->prices()->where('currency_code', 'NGN')->first();
            if ($ngnPrice && $ngnPrice->list_price !== null) {
                return round($ngnPrice->list_price / $listExchangeRate, 2);
            }
        }
        
        return $this->list_price; // Final fallback to default list_price
    }
    
    public function hasUsdPrice()
    {
        $usdPrice = $this->prices()->where('currency_code', 'USD')->first();
        return $usdPrice && $usdPrice->price !== null;
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySubcategory($query, $subcategory)
    {
        return $query->where('subcategory', $subcategory);
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function syncTags($tagNames)
    {
        // Remove old tags count
        if ($this->tags) {
            foreach ($this->tags as $oldTag) {
                $tag = Tag::where('name', $oldTag)->first();
                if ($tag) $tag->decrementCount();
            }
        }

        // Add new tags and increment count
        $this->tags = $tagNames;
        foreach ($tagNames as $tagName) {
            Tag::createOrIncrement($tagName);
        }
        $this->save();
    }
}