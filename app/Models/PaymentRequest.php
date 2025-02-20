<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;
    protected $table = 'payment_request';
    protected $fillable = [
        'user_id',	
        'email',	
        'phone',	
        'amount',	
        'currency_symbol',	
        'comment',	
        'payment_status',
    ];
    public function userInfo()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

