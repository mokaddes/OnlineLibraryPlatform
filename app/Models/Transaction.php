<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function book()
    {
        return $this->belongsTo(Product::class, 'book_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
