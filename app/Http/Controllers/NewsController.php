<?php

namespace App\Http\Controllers;

use App\News;
use App\Category;
use App\CategoryRel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return News::with('add_category')
            ->statusCheck()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function list(Request $request): ?\Illuminate\Pagination\LengthAwarePaginator
    {
        $ids = CategoryRel::where('category_id', $request->id)
            ->pluck('news_id');
        return News::with('add_category')
            ->statusCheck()
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function archive(Request $request): ?\Illuminate\Pagination\LengthAwarePaginator
    {
        return News::with('add_category')
            ->statusCheck()
            ->whereYear('created_at', $request->y)
            ->whereMonth('created_at', $request->m)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function detail(Request $request): ?\App\News
    {
        return News::with('add_category')
            ->statusCheck()
            ->find($request->id);
    }

    public function getCategorys(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::select('category.id', 'category.title', 'category.text')
            ->leftJoin('category_rel', 'category_rel.category_id', '=', 'category.id')
            ->leftJoin('news', 'news.id', '=', 'category_rel.news_id')
            ->where('news.status', config('const.STATUS_ON'))
            ->groupBy('category.id')
            ->orderByRaw('category.sort_num IS NULL ASC')
            ->orderBy('category.sort_num', 'ASC')
            ->get();
    }

    public function getArchives(): \Illuminate\Database\Eloquent\Collection
    {
        return News::select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name, COUNT(*) post_count'))
            ->statusCheck()
            ->groupBy('year')
            ->groupBy('month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }
}
