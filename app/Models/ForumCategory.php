<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;
    protected $table ='forum_categories';
    protected $appends = ['post_count'];

    public function forums()
    {
        return $this->hasMany(Forum::class, 'category_id');

    }
    public function getPostCountAttribute()
    {
        return $this->forums()->where('status', 1)->count();
    }
}
