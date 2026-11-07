<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'is_active',
        'review_banned_at',
        'review_ban_reason',
        'last_login_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'review_banned_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function employee()
    {
        // ✅ Added withTrashed() so Super Admin can see if the Owner deleted the employee
        return $this->hasOne(Employee::class)->withTrashed();
    }

    public function isReviewBanned(): bool
    {
        return !is_null($this->review_banned_at);
    }

    public function getLastLoginLabelAttribute(): string
    {
        if (!$this->last_login_at) {
            return 'Never';
        }

        return $this->last_login_at->diffForHumans() . ' (' . $this->last_login_at->format('M d, Y h:i A') . ')';
    }
}
