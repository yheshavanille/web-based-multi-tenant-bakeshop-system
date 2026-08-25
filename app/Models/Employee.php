<?php

namespace App\Models;

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

    /**
     * Check if the associated user was deleted by Super Admin
     */
    public function isUserDeletedBySuperAdmin()
    {
        return $this->user && $this->user->trashed();
    }

    /**
     * Check if the associated user is suspended
     */
    public function isUserSuspended()
    {
        return $this->user && !$this->user->trashed() && !$this->user->is_active;
    }

    /**
     * Check if the associated user is missing
     */
    public function isUserMissing()
    {
        return !$this->user;
    }

    /**
     * Get the appropriate status label for display
     */
    public function getStatusLabel()
    {
        if ($this->isUserMissing()) {
            return 'Account Missing';
        }
        if ($this->isUserDeletedBySuperAdmin()) {
            return 'Account Deleted by Super Admin';
        }
        if ($this->isUserSuspended()) {
            return 'Account Suspended by Admin';
        }
        if ($this->trashed()) {
            return 'Deleted';
        }
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Get the status color for display
     */
    public function getStatusColor()
    {
        if ($this->isUserMissing() || $this->isUserDeletedBySuperAdmin() || $this->isUserSuspended()) {
            return 'red';
        }
        if ($this->trashed()) {
            return 'red';
        }
        return $this->is_active ? 'green' : 'red';
    }

    /**
     * Check if employee can be restored (only if user still exists and is not deleted)
     */
    public function canBeRestored()
    {
        return $this->trashed() && $this->user && !$this->user->trashed();
    }

    /**
     * Check if employee can be edited
     */
    public function canBeEdited()
    {
        return $this->user && !$this->user->trashed() && !$this->trashed();
    }

    /**
     * Check if employee actions are available
     */
    public function hasAvailableActions()
    {
        return $this->user && !$this->user->trashed() && !$this->trashed();
    }
}
