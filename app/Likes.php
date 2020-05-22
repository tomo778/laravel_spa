<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes';

    public $timestamps = true;

    protected $fillable = [
        'news_id',
        'user_id',
    ];
}