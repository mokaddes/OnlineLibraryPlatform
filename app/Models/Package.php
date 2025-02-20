<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'price',
        'price_ngn',
        'duration',
        'offerings',
        'library',
        'book',
        'blog',
        'forum',
        'club',
        'plan_id',
        'plan_id2',
        'flutterwave_plan_data',
        'paypal_plan_data',
    ];

    protected $appends = ['is_subscribed'];

    public function users()
    {
        return $this->hasMany(User::class, 'plan_id');
    }


    public function getIsSubscribedAttribute()
    {
        if (auth('api')->check() || auth()->check()) {
            $id = auth('api')->user()->id ?? auth()->user()->id ;
            return $this->hasOne(UserPlan::class, 'package_id')
                ->where('user_id', $id)
                ->where('status', 1)
                ->where('expired_date', '>', now())->exists();
        }
        return $this->hasOne(UserPlan::class, 'package_id')
            ->where('user_id', 0)
            ->where('status', 'none')->exists();
    }
}
