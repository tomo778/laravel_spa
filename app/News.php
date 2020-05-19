<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeStatusCheck(Object $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('news.status', config('const.STATUS_ON'));
    }
}
