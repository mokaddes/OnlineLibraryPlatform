<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey   = 'pk_no';

    public function details(){
        return $this->hasMany(TicketDetail::class, 'ticket_id', 'pk_no');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

   

    


}
