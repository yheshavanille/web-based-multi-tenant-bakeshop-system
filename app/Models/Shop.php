<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shop_name',
        'shop_image',
        'description',
        'address',
        'user_id',
        'opening_time',
        'closing_time',
        'business_permit_path',
        'business_permit_expires_at',
        'pending_business_permit_path',
        'pending_business_permit_expires_at',
        'business_permit_review_status',
        'business_permit_review_note',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'business_permit_expires_at' => 'date',
        'pending_business_permit_expires_at' => 'date',
    ];

    public function getPermitValidityStatusAttribute(): string
    {
        if (!$this->business_permit_path || !$this->business_permit_expires_at) {
            return 'not_submitted';
        }

        if ($this->business_permit_expires_at->lt(today())) {
            return 'expired';
        }

        if ($this->business_permit_expires_at->lte(today()->addDays(30))) {
            return 'expiring_soon';
        }

        return 'valid';
    }

    public function getPermitValidityLabelAttribute(): string
    {
        if ($this->permit_validity_status !== 'expiring_soon') {
            return match ($this->permit_validity_status) {
                'valid' => 'Valid',
                'expired' => 'Expired',
                default => 'No approved permit',
            };
        }

        $daysRemaining = (int) today()->diffInDays($this->business_permit_expires_at, false);

        return match ($daysRemaining) {
            0 => 'Expires today',
            1 => 'Expires tomorrow',
            default => "Expires in {$daysRemaining} days",
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    // ✅ ADD THIS RELATIONSHIP
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // public function scopeGetData($data)
    // {
    //     return $data->with('user')
    //         ->withCount('orders')
    //         ->get();;
    // }
}
