<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_company_id',
        'shipping_zone_id',
        'calculation_type',
        'min_value',
        'max_value',
        'cost',
        'free_shipping_threshold'
    ];

    public function company()
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }
}
