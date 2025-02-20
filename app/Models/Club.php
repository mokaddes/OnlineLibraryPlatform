<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    public function members() {
        return $this->hasMany(ClubMember::class, 'club_id');
    }

    public function posts() {
        return $this->hasMany(ClubPost::class, 'club_id');
    }

    public function createdByAdmin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function createdByUser() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clubAdmin() {
        return $this->user_id !== null ? $this->createdByUser() : $this->createdByAdmin();
    }

}
