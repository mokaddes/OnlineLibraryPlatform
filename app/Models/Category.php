<?php

namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;


    public function publishedBook()
    {
        return $this->hasMany(Product::class, 'category_id')->where('status', 10);
    }
}
