<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coverage_areas', 'is_active'];

    protected $casts = [
        'name' => 'array',
        'coverage_areas' => 'array', // List of cities/regions
        'is_active' => 'boolean',
    ];
}
