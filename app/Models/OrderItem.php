<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'digital_asset_id',
        'asset_name',
        'quantity',
        'price',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'last_downloaded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function digitalAsset()
    {
        return $this->belongsTo(DigitalAsset::class);
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count', 1, ['last_downloaded_at' => now()]);
    }
}
