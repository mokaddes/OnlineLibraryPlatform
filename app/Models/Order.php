<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function userInfo()
    {
        return $this->belongsTo(User::class, 'order_by', 'id');
    }

}
