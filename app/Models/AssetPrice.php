<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetPrice extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'asset_id',
        'currency_code',
        'price',
        'list_price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'list_price' => 'decimal:2'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(DigitalAsset::class, 'asset_id');
    }
}