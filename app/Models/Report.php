<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public function reported()
    {
        return $this->belongsTo(User::class, 'reported_id');
    }
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
