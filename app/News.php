<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    public $timestamps = true;

    protected $fillable = [
        'status',
        'title',
        'text',
    ];

    /** JSONに含めるアクセサ */
    protected $appends = [
        'likes_count', 'liked_by_user',
    ];

    public function category_rel(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(CategoryRel::class);
    }

    public function add_category(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            CategoryRel::class,
        );
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            Likes::class
        )->withTimestamps();
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes->count();
    }

    public function getLikedByUserAttribute(): bool
    {
        if (Auth::guest()) {
            return false;
        }
        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    public function scopeStatusCheck(Object $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('news.status', config('const.STATUS_ON'));
    }
}
