<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    protected $fillable = [
        'customer_id',
        'shop_id',
        'branch_id',
        'order_id',
        'rating',
        'employee_rating',
        'review',
        'edit_count',
        // ✅ Moderation fields
        'moderation_status',
        'flagged_by',
        'flag_reason',
        'flag_notes',
        'flagged_at',
        'moderated_by',
        'moderator_notes',
        'moderated_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'employee_rating' => 'integer',
        'edit_count' => 'integer',
        'flagged_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    // ✅ Scopes
    public function scopeVisible($query)
    {
        return $query->whereIn('moderation_status', ['visible', 'kept']);
    }

    public function scopeFlagged($query)
    {
        return $query->where('moderation_status', 'pending_review');
    }

    // ✅ Helpers
    public function isFlagged(): bool
    {
        return $this->moderation_status === 'pending_review';
    }

    public function isRemoved(): bool
    {
        return $this->moderation_status === 'removed';
    }

    public function isKept(): bool
    {
        return $this->moderation_status === 'kept';
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function flaggedBy()
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }

    public function moderatedBy()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
