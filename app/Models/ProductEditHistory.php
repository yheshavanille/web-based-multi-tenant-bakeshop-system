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

    // ✅ Helper method to get display notes
    public function getNotesAttribute()
    {
        return match ($this->field) {
            'created' => 'Product created',
            'deleted' => 'Product deleted',
            'restored' => 'Product restored',
            'stock' => "Stock updated from {$this->old_value} to {$this->new_value}",
            'order_status' => "Order status changed from {$this->old_value} to {$this->new_value}",
            'name' => "Name changed from '{$this->old_value}' to '{$this->new_value}'",
            'price' => "Price changed from ₱{$this->old_value} to ₱{$this->new_value}",
            'category_id' => "Category changed",
            'description' => "Description updated",
            'image_url' => "Image {$this->new_value}",
            default => ucfirst(str_replace('_', ' ', $this->field)),
        };
    }
}
