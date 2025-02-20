<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $casts = ['marc_data' => 'json'];

    protected $fillable=['title', 'slug'];


    protected static function boot()
    {
        parent::boot();

        // On creating event
        static::creating(function ($product) {
            $product->generateUniqueSlug();
        });

        // On updating event
        static::updating(function ($product) {
            $product->generateUniqueSlug();
        });
    }

    public function generateUniqueSlug()
    {
        $slug = Str::slug($this->title);
        $count = 0;

        // Check if a product with the same slug exists, excluding the current product by ID
        while (Product::where('id', '!=', $this->id)->where('slug', $slug)->exists()) {
            $count++;
            $slug = Str::slug($this->title) . '-' . $count;
        }

        $this->slug = $slug;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function borrowedBooks(): HasMany
    {
        return $this->hasMany(BorrowedBook::class, 'product_id');
    }

    public function favouriteBooks(): HasMany
    {
        return $this->hasMany(ProductFavourite::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class, 'book_id');
    }
    public function AvgReview()
    {
        $avg = $this->reviews()->avg('rating');
        return number_format($avg, 2);
    }

    public function productViews(): HasMany
    {
        return $this->hasMany(ProductView::class, 'product_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function product_category(): HasMany
    {
        return $this->hasMany(ProductCategoryMap::class, 'product_id', 'id');
    }

}
