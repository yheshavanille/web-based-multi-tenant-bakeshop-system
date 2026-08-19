<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductEditHistory extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
