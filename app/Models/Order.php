<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'shop_id',
        'branch_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'pickup_time',
        'notes',
        'service_review',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function serviceReview()
    {
        return $this->hasOne(ServiceReview::class);
    }
}
