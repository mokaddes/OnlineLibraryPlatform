<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubMember extends Model
{
    protected $appends = ['isOwner'];
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function club() {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function getIsOwnerAttribute()
    {
        return $this->club()->where('user_id', $this->user_id)->exists();
    }
}
