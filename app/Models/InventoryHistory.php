<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    protected $fillable = [
        'product_id',
        'branch_id',
        'user_id',
        'type',         // stock_in | stock_out | adjustment
        'quantity',     // signed: +2 or -3
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
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'stock_in' => 'Stock In',
            'stock_out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'stock_in' => 'green',
            'stock_out' => 'red',
            'adjustment' => 'yellow',
            default => 'gray',
        };
    }
}
