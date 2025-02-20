<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodePackage extends Model
{
    protected $table = 'promocode_package';
    use HasFactory;

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function used()
    {
        return $this->hasMany(PromocodePackageUsed::class, 'promocode_package_id');
    }

    public function usedUser()
    {
        return $this->hasManyThrough(User::class, PromocodePackageUsed::class, 'promocode_package_id', 'id', 'id', 'user_id');
    }

    public function userPlan()
    {
        return $this->hasMany(UserPlan::class, 'package_promocode_id');
    }

    public function planUser()
    {
        return $this->hasManyThrough(User::class, UserPlan::class, 'package_promocode_id', 'id', 'id', 'user_id')
            ->where('user_plans.status', '=', 1);
    }
}
