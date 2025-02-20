<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;
    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }


    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id', 'pk_no');
    }

}
