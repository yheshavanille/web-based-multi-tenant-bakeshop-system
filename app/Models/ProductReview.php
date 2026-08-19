<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'customer_id',
        'shop_id',
        'order_id',
        'product_id',
        'rating',
        'review',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
