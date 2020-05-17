<?php

namespace app\Libs;

class Common
{
    /**
     * フリーワード検索のqueryを追加する
     *
     * @param Object $query
     * @param string 検索文字列
     * @param array 検索対象カラム
     * @return Object $query
     */
    public static function fw_search(Object $query, string $s, array $tmp2)
    {
        $tmp = array();
        $tmp = mb_convert_kana($s, "s", "UTF-8");
        $tmp = trim($tmp);
        $tmp = explode(" ", $tmp);
        $tmp = array_filter($tmp);
        foreach ($tmp as $key => $value) {
            $query->where(
                function ($query) use ($value, $tmp2) {
                    foreach ($tmp2 as $key2 => $value2) {
                        $query->orwhere($value2, 'like', '%' . $value . '%');
                    }
                }
            );
        }
        return $query;
    }
}
