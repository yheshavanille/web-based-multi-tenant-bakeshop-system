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
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
