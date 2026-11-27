<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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
        'business_permit_last_reminder_key',
        'closure_request_status',
        'closure_request_reason',
        'closure_requested_at',
        'closure_reviewed_at',
        'closure_review_note',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'business_permit_expires_at' => 'date',
        'pending_business_permit_expires_at' => 'date',
        'closure_requested_at' => 'datetime',
        'closure_reviewed_at' => 'datetime',
    ];

    public static function nextBusinessPermitExpiryDate(
        ?Carbon $approvalDate = null,
        ?Carbon $currentExpiryDate = null
    ): string {
        $approvalDate = ($approvalDate ?? today())->copy()->startOfDay();
        $referenceDate = $currentExpiryDate && $currentExpiryDate->greaterThan($approvalDate)
            ? $currentExpiryDate->copy()->startOfDay()
            : $approvalDate;
        $expiryYear = $referenceDate->format('m-d') < '02-28'
            ? $referenceDate->year
            : $referenceDate->year + 1;

        return sprintf('%04d-02-28', $expiryYear);
    }

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

    public function unresolvedOrders(): HasMany
    {
        return $this->orders()
            ->where(function ($query) {
                $query->whereNull('is_parent_order')
                    ->orWhere('is_parent_order', false);
            })
            ->where(function ($query) {
                $query->whereIn('status', ['pending', 'preparing', 'ready_for_pickup', 'partially_completed'])
                    ->orWhere(function ($paymentQuery) {
                        $paymentQuery->whereIn('payment_status', ['pending', 'partially_paid'])
                            ->whereNotIn('status', ['cancelled', 'no_show']);
                    });
            });
    }

    public function hasUnresolvedOrders(): bool
    {
        return $this->unresolvedOrders()->exists();
    }

    // public function scopeGetData($data)
    // {
    //     return $data->with('user')
    //         ->withCount('orders')
    //         ->get();;
    // }
}
