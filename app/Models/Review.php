<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Review extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'reviewer_type',
        'reviewer_id',
        'reviewable_type',
        'reviewable_id',
        'review_data',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'review_data' => 'array',
        'is_approved' => 'boolean',
    ];

    public function reviewer()
    {
        return $this->morphTo();
    }

    public function reviewable()
    {
        return $this->morphTo();
    }
}
