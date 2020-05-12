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

    public function category_rel()
    {
        return $this->hasMany('App\CategoryRel');
    }

    /**
     * userのスキルを取得
     */
    public function add_category()
    {
        return $this->belongsToMany(
            'App\Category',
            'category_rel',
        );
    }
}
