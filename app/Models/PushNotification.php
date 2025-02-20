<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $casts =[
        'user_ids' => 'array',
        'total_send' => 'int',
        'total_success' => 'int',
        'status' => 'int',
    ];
}
