<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFavourite extends Model
{
    use HasFactory;

    public function book()
    {
        return $this->belongsTo(Product::class, 'product_id');

    }

    public function user()
    {
        return $this->belongsTo(Product::class, 'user_id');
    }
}
