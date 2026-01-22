<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NewsletterSubscriber extends Model
{
    use HasUuids;

    protected $fillable = [
        'subscriber_type',
        'subscriber_id',
        'email',
        'name',
        'is_active',
        'subscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
    ];

    public function subscriber()
    {
        return $this->morphTo();
    }
}
