<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_view_id',
        'user_id',
        'total_view',
        'page_stay_time',
        'page_total_time'
    ];


    public function product_view()
    {
        return $this->belongsTo(ProductView::class, 'product_view_id', 'id');
    }
}
