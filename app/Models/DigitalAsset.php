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
        $price = $this->prices()->where('currency_code', $currencyCode)->first();
        return $price && $price->price !== null ? $price->price : $this->price;
    }
    
    public function getListPriceForCurrency($currencyCode)
    {
        $priceRecord = $this->prices()->where('currency_code', $currencyCode)->first();
        
        // Only return list price if it exists for THIS specific currency
        if ($priceRecord && $priceRecord->list_price !== null && $priceRecord->list_price > 0) {
            return $priceRecord->list_price;
        }
        
        // Don't fallback to default list_price - return null if no list price for this currency
        return null;
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