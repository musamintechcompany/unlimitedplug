<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'category_id',
        'subcategory_id',
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
        'is_active',
        'badge',
        'license_type',
        'admin_id',
        'reviewed_at',
        'downloads',
    ];

    protected $casts = [
        'media' => 'array',
        'tags' => 'array',
        'features' => 'array',
        'requirements' => 'array',
        'file' => 'array',
        'price' => 'decimal:2',
        'list_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
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
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function approvedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('is_approved', true);
    }

    public function getAverageRating()
    {
        $approved = $this->approvedReviews()->get();
        if ($approved->isEmpty()) return 0;
        
        $sum = $approved->sum(function($review) {
            return $review->review_data['rating'] ?? 0;
        });
        
        return round($sum / $approved->count(), 1);
    }

    public function getReviewCount()
    {
        return $this->approvedReviews()->count();
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
