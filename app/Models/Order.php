<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'orderable_type',
        'orderable_id',
        'order_number',
        'payment_id',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'status',
        'transaction_reference',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->orderable();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }
}
