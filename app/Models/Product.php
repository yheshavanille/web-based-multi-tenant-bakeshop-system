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
    ];

    protected $dates = ['deleted_at'];

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
}
