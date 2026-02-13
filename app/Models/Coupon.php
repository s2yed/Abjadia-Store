<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type', // fixed, percentage
        'value',
        'expiry_date',
        'usage_limit',
        'used_count',
        'min_order_value',
        'max_discount_value',
        'is_active',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount_value' => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_coupon');
    }

    public function shippingZones()
    {
        return $this->belongsToMany(ShippingZone::class, 'coupon_shipping_zone');
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expiry_date && now()->gt($this->expiry_date)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
