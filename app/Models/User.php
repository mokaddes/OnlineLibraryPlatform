<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property $id
 */
class User extends Authenticatable implements JWTSubject,MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country',
        'country_code',
        'role_id',
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'image',
        'user_type',
        'dial_code',
        'country_code',
        'email_verified_at',
        'device_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function country_code(): string
    {
        return strtoupper($this->attributes['country_code']);
    }

    public function productViews()
    {
        return $this->hasManyThrough(ProductView::class, Product::class);
    }

    public function borrowedAuthorBooks()
    {
        return $this->hasManyThrough(BorrowedBook::class, Product::class, 'user_id', 'product_id');
    }

    public function favorites()
    {
        return $this->hasMany(ProductFavourite::class, 'user_id')->whereHas('book', function ($q){
            $q->where('status', '10');
        });
    }
    public function borrowed()
    {

        $query = $this->hasMany(BorrowedBook::class, 'user_id')->whereHas('book', function ($q){
            $q->where('status', '10');
        });
        if ($this->role_id == 3) {
            $query->where('is_valid', '1')->where('is_institution', '1');
        }else{
            $query->where(function ($q){
                $q->where('borrowed_enddate', '>=', now())
                    ->orWhere('is_bought', 1);
            })->get();
        }
        return $query;
    }
    public function viewed()
    {
        return $this->hasMany(ProductView::class, 'user_id')->whereHas('book');
    }

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class, 'user_id');
    }

    public function blogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'user_id');
    }

    public function blogPostLikes(): HasMany
    {
        return $this->hasMany(BlogPostLike::class, 'user_id');
    }

    public function bookReviews(): HasMany
    {
        return $this->hasMany(BookReview::class, 'user_id');
    }

    public function clubComments(): HasMany
    {
        return $this->hasMany(ClubComment::class, 'user_id');
    }

    public function clubMembers(): HasMany
    {
        return $this->hasMany(ClubMember::class, 'user_id');
    }

    public function clubPosts(): HasMany
    {
        return $this->hasMany(ClubPost::class, 'created_by');
    }

    public function forums(): HasMany
    {
        return $this->hasMany(Forum::class, 'created_by');
    }

    public function forumComments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'created_by');
    }

    public function forumPostLikes(): HasMany
    {
        return $this->hasMany(ForumPostLike::class, 'created_by');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function plan(): HasMany
    {
        return $this->hasMany(UserPlan::class, 'user_id');
    }

    public function currentUserPlan(): HasOne
    {
        return $this->hasOne(UserPlan::class, 'user_id')->where('status', 1)->where('expired_date', '>', now());
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'plan_id');
    }
}
