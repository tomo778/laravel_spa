<?php

namespace App\Http\Controllers;

use App\News;
use App\Category;
use App\CategoryRel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function __construct()
    {
        //0.1秒遅らせる
        //usleep(150000);
    }

    public function index()
    {
        return News::with('add_category')
            ->StatusCheck()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function list(Request $request)
    {
        // $news = News::with('add_category')
        //     ->whereHas('add_category', function ($query) use ($request) {
        //         $query->where('category.id', $request->id);
        //     })
        //     ->orderBy('id', 'desc')
        //     ->paginate(10);

        $results = CategoryRel::select('news_id')
            ->where('category_id', $request->id)
            ->get()->toArray();
        foreach ($results as $k => $v) {
            $ids[] = $v['news_id'];
        }
        $news = News::with('add_category')
            ->StatusCheck()
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $news;
    }

    public function archive(Request $request)
    {
        return News::with('add_category')
            ->StatusCheck()
            ->whereYear('created_at', $request->y)
            ->whereMonth('created_at', $request->m)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function detail(Request $request)
    {
        return News::with('add_category')
            ->StatusCheck()
            ->find($request->id);
    }

    public function getCategorys()
    {
        return Category::select('category.id', 'category.title', 'category.text')
            ->leftJoin('category_rel', 'category_rel.category_id', '=', 'category.id')
            ->leftJoin('news', 'news.id', '=', 'category_rel.news_id')
            ->where('news.status', 1)
            ->groupBy('category.id')
            ->orderBy('category.id', 'asc')
            ->get();
    }

    public function getArchives()
    {
        return News::select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name, COUNT(*) post_count'))
            ->StatusCheck()
            ->groupBy('year')
            ->groupBy('month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()->toArray();
        // foreach ($arr as $k => $v) {
        //     $archives[$v['year']][] = $v;
        // }
    }
}
