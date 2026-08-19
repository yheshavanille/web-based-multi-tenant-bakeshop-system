<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    protected $fillable = [
        'customer_id',
        'shop_id',
        'branch_id',
        'order_id',
        'rating',
        'employee_rating',
        'review',
    ];

    protected $casts = [
        'rating' => 'integer',
        'employee_rating' => 'integer',
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
