<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';
    protected $appends = ['post_count'];

    public function blogs()
    {
        return $this->hasMany(BlogCategoryMap::class, 'blog_category_id');
    }

    public function blogPost()
    {
        return $this->belongsToMany(Blog::class, 'blog_category_map');
    }

    public function getPostCountAttribute()
    {
        return $this->blogPost()->where('status', 1)->count();
    }

}
