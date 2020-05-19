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

    public function scopeDeleteRel(Object $query, int $id): void
    {
        $query->where('news_id', '=', $id)->delete();
    }

    public function scopeInsertRel(Object $query, array $categorys, int $last_id): \Illuminate\Database\Eloquent\Builder
    {
        foreach ($categorys as $k => $v) {
            $tmp['news_id'] = $last_id;
            $tmp['category_id'] = $v;
            $data[] = $tmp;
        }
        return $query->insert($data);
    }
}
