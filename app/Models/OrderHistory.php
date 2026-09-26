<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'branch_id',
        'user_id',
        'status',       // out | cancelled | no_show
        'quantity',     // signed: -1 for sale, +1 for restore
        'old_stock',
        'new_stock',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'old_stock' => 'integer',
        'new_stock' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'out' => 'Order Sale',
            'cancelled' => 'Order Cancel',
            'no_show' => 'No Show',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'out' => 'blue',
            'cancelled' => 'orange',
            'no_show' => 'orange',
            default => 'gray',
        };
    }
}
