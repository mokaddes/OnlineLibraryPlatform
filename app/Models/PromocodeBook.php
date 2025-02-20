<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodeBook extends Model
{
    use HasFactory;


    protected $casts = ['book_ids' => 'array'];



    public function books()
    {
        return Product::whereIn('id', $this->book_ids)->get();
    }

    public function used()
    {
        return $this->hasMany(PromocodeBookUsed::class, 'promocode_book_id');
    }

    public function borrowed()
    {
        return $this->hasMany(BorrowedBook::class, 'promocode_book_id');
    }
}
