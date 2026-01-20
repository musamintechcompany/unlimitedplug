<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'product_id',
        'currency_code',
        'price',
        'list_price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'list_price' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
