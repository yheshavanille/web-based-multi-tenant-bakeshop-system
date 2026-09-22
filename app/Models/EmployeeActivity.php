<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeActivity extends Model
{
    protected $fillable = [
        'employee_id',
        'shop_id',
        'action',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function employee()
    {
        // ✅ withTrashed so activity log still works when employee was soft-deleted
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    /**
     * Human-readable labels for each action type.
     */
    public static function actionLabels(): array
    {
        return [
            'password_changed_self'     => 'Changed own password',
            'password_changed_by_owner' => 'Password reset by owner',
            'profile_updated'           => 'Updated own profile',
            'employee_created'          => 'Account created by owner',
        ];
    }

    /**
     * Accessor: $activity->action_label
     */
    public function getActionLabelAttribute()
    {
        return self::actionLabels()[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }
}
