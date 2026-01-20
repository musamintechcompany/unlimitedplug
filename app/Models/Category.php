<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
        'created_by_type',
        'created_by_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class)->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function creator()
    {
        if ($this->created_by_type === 'admin') {
            return $this->belongsTo(Admin::class, 'created_by_id');
        }
        return $this->belongsTo(User::class, 'created_by_id');
    }
}