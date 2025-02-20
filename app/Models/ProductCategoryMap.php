<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryMap extends Model
{
    use HasFactory;

    protected $table = 'product_category_map';

    protected $fillable = [
        'product_id',
        'product_category_id',
    ];
}
