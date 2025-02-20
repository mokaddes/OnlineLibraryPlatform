<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'is_valid',
        'is_institution',
        'borrowed_startdate',
        'borrowed_enddate',
        'borrowed_nextdate',
        'is_bought'
    ];

    public function book()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
