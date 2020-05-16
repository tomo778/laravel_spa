<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'text',
    ];
}
