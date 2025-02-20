<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumComment extends Model
{
    use HasFactory;
    protected $table = 'forum_comments';
    protected $appends = ['likes', 'dislikes'];

    public function getUser()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function replies()
    {
        return $this->hasMany(ForumComment::class, 'comment_parent_id');
    }
    public function getForum()
    {
        return $this->belongsTo(Forum::class,'forum_id');
    }
    public function likeDislikes(): HasMany
    {
        return $this->hasMany(ForumPostLike::class,'comment_id');
    }
    public function getLikesAttribute(): int
    {
        return $this->likeDislikes()->where('likedislike',1)->count();
    }
    public function getDislikesAttribute(): int
    {
        return $this->likeDislikes()->where('likedislike',0)->count();
    }
}
