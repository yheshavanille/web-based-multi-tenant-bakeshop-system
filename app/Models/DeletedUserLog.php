<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedUserLog extends Model
{
    protected $table = 'deleted_users_log';

    protected $fillable = [
        'original_user_id',
        'name',
        'email',
        'phone',
        'shop_name',
        'roles',
        'deleted_by',
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
