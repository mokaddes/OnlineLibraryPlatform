<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'total_view',
    ];

    public function book()
    {
        return $this->belongsTo(Product::class, 'product_id');

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function page_views()
    {
        return $this->hasMany(ProductPageView::class, 'product_view_id');
    }
}
