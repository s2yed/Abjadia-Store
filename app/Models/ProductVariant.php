<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_name', // e.g., Color, Size
        'attribute_value', // e.g., Red, XL
        'additional_price',
        'stock_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
