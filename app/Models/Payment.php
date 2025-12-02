<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'session_id',
        'payment_id',
        'amount',
        'currency',
        'pay_currency',
        'status',
        'actually_paid',
        'pay_amount',
        'payment_url',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'actually_paid' => 'decimal:8',
        'pay_amount' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}