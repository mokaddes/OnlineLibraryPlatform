<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 */
class UserPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'package_id',
        'plan_id',
        'customer_id',
        'subscription_id',
        'expired_date',
        'status',
    ];
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
