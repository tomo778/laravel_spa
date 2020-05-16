<?php

namespace app\Libs;

class Common
{
    /**
     * フリーワード検索のqueryを追加する
     *
     * @param obj $query
     * @param strings 検索文字列
     * @param array 検索対象カラム
     * @return obj $query
     */
    public static function fw_search($query, $s, $tmp2)
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

    // public static function dl_file()
    // {
    // 	// ダウンロード対象ファイル
    // 	$path = "/path/to/download/file.doc";
    // 	$name = "ダウンロード.doc";
    // 	$mimeType = "application/msword";

    // 	header("Content-Length: " . filesize($path));
    // 	header("Content-Disposition: attachment; filename=\"{$fileName}\"");
    // 	header("Accept-Ranges: bytes");
    // 	header("Content-type: {$mimeType}");

    // 	// 実ファイル呼び出し
    // 	$handle = fopen($path, 'rb');
    // 	while (!feof($handle)) {
    // 		echo fread($handle, 4096);
    // 		ob_flush();
    // 		flush();
    // 	}
    // 	fclose($handle);
    // }
}
