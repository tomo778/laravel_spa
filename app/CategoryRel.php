<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryRel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_rel';

    //public $timestamps = true;

    protected $fillable = [
        'news_id',
        'category_id',
    ];

    public function scopeDeleteRel($query, $id)
    {
        $query->where('news_id', '=', $id)->delete();
    }

    // public function category()
    // {
    //     return $this->hasOne('App\Category');
    // }

    public function scopeInsertRel($query, $categorys, $last_id)
    {
        foreach ($categorys as $k => $v) {
            $tmp['news_id'] = $last_id;
            $tmp['category_id'] = $v;
            $data[] = $tmp;
        }
        return $query->insert($data);
    }
}
