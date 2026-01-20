<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Coupon extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_uses',
        'max_uses_per_user',
        'valid_from',
        'valid_until',
        'applicable_to',
        'applicable_ids',
        'created_by',
        'created_by_type',
        'is_active',
        'used_count',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'applicable_ids' => 'array',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function creator()
    {
        if ($this->created_by_type === 'admin') {
            return $this->belongsTo(Admin::class, 'created_by');
        }
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isValid($cartTotal = 0, $userId = null, $productIds = [], $currencySymbol = '$')
    {
        // Check if active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is currently inactive'];
        }

        // Check date validity
        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return ['valid' => false, 'message' => 'This coupon is not valid yet. Valid from ' . $this->valid_from->format('M d, Y')];
        }
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return ['valid' => false, 'message' => 'This coupon expired on ' . $this->valid_until->format('M d, Y')];
        }

        // Check minimum purchase
        if ($this->min_purchase && $cartTotal < $this->min_purchase) {
            $needed = $this->min_purchase - $cartTotal;
            return ['valid' => false, 'message' => 'Minimum purchase of ' . $currencySymbol . number_format($this->min_purchase, 2) . ' required. Add ' . $currencySymbol . number_format($needed, 2) . ' more to use this coupon'];
        }

        // Check max uses
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return ['valid' => false, 'message' => 'This coupon has reached its usage limit'];
        }

        // Check per user limit
        if ($userId) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->max_uses_per_user) {
                $remaining = $this->max_uses_per_user - $userUsageCount;
                if ($this->max_uses_per_user == 1) {
                    return ['valid' => false, 'message' => 'You have already used this coupon'];
                }
                return ['valid' => false, 'message' => 'You have reached the usage limit for this coupon (' . $this->max_uses_per_user . ' times)'];
            }
        }

        // Check applicable products/categories
        if ($this->applicable_to !== 'all' && !empty($this->applicable_ids)) {
            if ($this->applicable_to === 'products') {
                $hasApplicable = !empty(array_intersect($productIds, $this->applicable_ids));
                if (!$hasApplicable) {
                    return ['valid' => false, 'message' => 'This coupon is not applicable to items in your cart'];
                }
            }
        }

        return ['valid' => true, 'message' => 'Coupon is valid'];
    }

    public function calculateDiscount($cartTotal)
    {
        if ($this->type === 'percentage') {
            return min(($cartTotal * $this->value) / 100, $cartTotal);
        }
        return min($this->value, $cartTotal);
    }
}
