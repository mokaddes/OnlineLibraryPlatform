<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $user_id
 * @property mixed $rating
 * @property mixed $book_id
 * @property mixed $review
 * @property int|mixed $status
 */
class BookReview extends Model
{
    use HasFactory;

    protected $casts =['user_id' => 'int'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');

    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'book_id');
    }
}
