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
        'review_banned_at',     // ✅ NEW
        'review_ban_reason',    // ✅ NEW
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'review_banned_at' => 'datetime', // ✅ NEW
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
        return $this->hasOne(Employee::class);
    }

    // ✅ NEW: helper to check if the user is banned from reviewing
    public function isReviewBanned(): bool
    {
        return !is_null($this->review_banned_at);
    }
}
