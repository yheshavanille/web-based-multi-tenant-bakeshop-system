<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_address',
        'contact_number',
        'shop_description',
        'business_permit',
        'business_permit_expires_at',
        'verified_business_permit_expires_at',
        'valid_id_path',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'business_permit_expires_at' => 'date',
        'verified_business_permit_expires_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
