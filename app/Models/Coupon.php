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
        'is_active',
    ];
}
