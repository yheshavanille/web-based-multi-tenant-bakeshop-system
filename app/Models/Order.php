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
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_method_detail',
        'payment_status',
        'payment_intent_id',
        'pickup_time',
        'notes',
        'cancelled_by',
        'service_review',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function isEPayment()
    {
        return in_array($this->payment_method, ['gcash', 'paymaya', 'paymongo']);
    }

    public function getPaymentMethodLabelAttribute()
    {
        // ✅ Changed: Show only "PayMongo" instead of "PayMongo (Gcash)" or "PayMongo (PayMaya)"
        if ($this->payment_method === 'paymongo') {
            return 'PayMongo';
        }
        return ucfirst(str_replace('_', ' ', $this->payment_method));
    }

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

    public function isCancelledByCustomer()
    {
        return $this->status === 'cancelled' && $this->cancelled_by === 'customer';
    }

    public function isCancelledByEmployee()
    {
        return $this->status === 'cancelled' && $this->cancelled_by === 'employee';
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
