<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function getCategoryMap(){
        return $this->hasMany(BlogCategoryMap::class);
    }

    public function tags()
    {
        return $this->hasMany(BlogTag::class);
    }
    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_map');
    }
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
