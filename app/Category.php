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

    // public function category_rel()
    // {
    //     return $this->hasMany('App\CategoryRel');
    // }
}
