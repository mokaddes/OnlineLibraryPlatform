<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryMap extends Model
{
    protected $table = 'blog_category_map';
    use HasFactory;

    public function getCategory()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }

}
