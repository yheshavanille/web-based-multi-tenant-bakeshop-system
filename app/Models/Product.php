<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category_id',
        'image_url',
        'shop_id',
        'discount_type',
        'discount_value',
        'discount_start',
        'discount_end',
    ];

    protected $dates = ['deleted_at', 'discount_start', 'discount_end'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_product')
            ->withPivot('stock')
            ->withTimestamps();
    }

    public function owner()
    {
        return $this->hasOneThrough(
            User::class,
            Shop::class,
            'id',
            'id',
            'shop_id',
            'user_id'
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function editHistories()
    {
        return $this->hasMany(ProductEditHistory::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    // ✅ DISCOUNT METHODS
    public function isDiscounted()
    {
        if ($this->discount_type === 'none' || $this->discount_value <= 0) {
            return false;
        }

        $now = now();
        if ($this->discount_start && $now->lt($this->discount_start)) {
            return false;
        }
        if ($this->discount_end && $now->gt($this->discount_end)) {
            return false;
        }

        return true;
    }

    public function getDiscountedPrice()
    {
        if (!$this->isDiscounted()) {
            return $this->price;
        }

        if ($this->discount_type === 'percentage') {
            return $this->price * (1 - $this->discount_value / 100);
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $this->price - $this->discount_value);
        }

        return $this->price;
    }

    public function getDiscountLabel()
    {
        if (!$this->isDiscounted()) {
            return null;
        }

        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '% OFF';
        }

        if ($this->discount_type === 'fixed') {
            return '₱' . number_format($this->discount_value, 2) . ' OFF';
        }

        return null;
    }
}
