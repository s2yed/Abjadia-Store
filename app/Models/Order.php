<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status', // pending, processing, shipped, completed, cancelled
        'total_price',
        'subtotal_after_discount',
        'shipping_cost',
        'shipping_zone_id',
        'paid_amount',
        'remaining_amount',
        'payment_method', // COD, Card
        'payment_status', // pending, paid
        'shipping_address', // JSON or text
        'billing_address',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
