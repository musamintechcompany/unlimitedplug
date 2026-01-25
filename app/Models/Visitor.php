<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Visitor extends Model
{
    use HasUuids;

    protected $fillable = [
        'visitor_id',
        'ip_address',
        'user_agent',
        'device_type',
        'country',
        'referrer',
        'user_id',
        'data',
        'first_visit',
        'last_visit',
    ];

    protected $casts = [
        'data' => 'array',
        'first_visit' => 'datetime',
        'last_visit' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
