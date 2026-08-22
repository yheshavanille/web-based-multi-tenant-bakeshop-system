<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'branch_id',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed(); // ✅ Added withTrashed()
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            'order_manager' => 'Order Manager',
            'inventory_manager' => 'Inventory Manager',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }
}
