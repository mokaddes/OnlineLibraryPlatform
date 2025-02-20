<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forum extends Model
{
    use HasFactory;

    protected $table = 'forums';
    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'slug',
        'created_by',
        'updated_by',
    ];

    public function getUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getComment(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'forum_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'forum_id');
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');

    }

    public function tags(): HasMany
    {
        return $this->hasMany(ForumTag::class, 'forum_id');
    }

}
