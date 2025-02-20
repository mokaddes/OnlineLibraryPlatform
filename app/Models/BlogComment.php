<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;
    protected $table = 'blog_comments';

    public function getUser()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function getBlog()
    {
        return $this->belongsTo(Blog::class,'blog_post_id');
    }
    public function likes()
    {
        return $this->hasMany(BlogPostLike::class, 'comment_id');
    }
}
