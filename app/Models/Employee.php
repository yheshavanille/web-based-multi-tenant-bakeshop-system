<?php

namespace App\Models;

use App\Models\EmployeeActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'shop_id',
        'branch_id',
        'role',
        'is_active',
        'deactivated_by',
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
        return $this->belongsTo(Shop::class)->withTrashed();
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

    public function isUserDeletedBySuperAdmin()
    {
        return $this->user && $this->user->trashed();
    }

    public function isUserSuspended()
    {
        return $this->user && !$this->user->trashed() && !$this->user->is_active;
    }

    public function isUserMissing()
    {
        return !$this->user;
    }

    public function getStatusLabel()
    {
        // 1. Check hard blocks first
        if ($this->isUserMissing()) {
            return 'Account Missing';
        }
        if ($this->isUserDeletedBySuperAdmin()) {
            return 'Account Deleted by Super Admin';
        }
        if ($this->trashed()) {
            return 'Deleted';
        }

        // 2. Check who deactivated the account FIRST
        if (!$this->is_active && $this->deactivated_by === 'super_admin') {
            return 'Suspended by Super Admin';
        }
        if (!$this->is_active && $this->deactivated_by === 'owner') {
            return 'Deactivated by Owner';
        }

        // 3. Fallback to user suspension check
        if ($this->isUserSuspended()) {
            return 'Suspended by Admin';
        }

        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getStatusColor()
    {
        if ($this->isUserMissing() || $this->isUserDeletedBySuperAdmin()) {
            return 'red';
        }
        if ($this->trashed()) {
            return 'red';
        }

        // Check deactivated_by first
        if (!$this->is_active && $this->deactivated_by === 'super_admin') {
            return 'red';
        }
        if (!$this->is_active && $this->deactivated_by === 'owner') {
            return 'yellow';
        }

        // Fallback
        if ($this->isUserSuspended()) {
            return 'red';
        }

        return $this->is_active ? 'green' : 'red';
    }

    public function canBeRestored()
    {
        return $this->trashed() && $this->user && !$this->user->trashed();
    }

    public function canBeEdited()
    {
        return $this->user && !$this->user->trashed() && !$this->trashed();
    }

    public function hasAvailableActions()
    {
        return $this->user && !$this->user->trashed() && !$this->trashed();
    }

    public function activities()
    {
        return $this->hasMany(EmployeeActivity::class)->latest();
    }
}
