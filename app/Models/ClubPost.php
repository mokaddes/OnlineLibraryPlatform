<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubPost extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function club() {
        return $this->belongsTo(Club::class, 'club_id');
    }
    public function comments()
    {
        return $this->hasMany(ClubComment::class, 'club_post_id');
    }
}
