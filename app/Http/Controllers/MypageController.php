<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Likes;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MypageController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    public function like(string $id): array
    {
        $news = News::where('id', $id)->with('likes')->first();

        if (!$news) {
            abort(404);
        }

        $news->likes()->detach(Auth::user()->id);
        $news->likes()->attach(Auth::user()->id);

        return ["news_id" => $id];
    }

    public function unlike(string $id): array
    {
        $news = News::where('id', $id)->with('likes')->first();

        if (!$news) {
            abort(404);
        }

        $news->likes()->detach(Auth::user()->id);

        return ["news_id" => $id];
    }

    public function likes(Request $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        // $ids = Likes::where('user_id', Auth::id())
        //     ->orderBy('created_at', 'desc')
        //     ->pluck('news_id')->toArray();
        // return News::with('add_category')
        //     ->statusCheck()
        //     ->whereIn('id', $ids)
        //     ->orderByRaw("field(id," . implode(',', $ids) . ")")
        //     ->paginate(10);

        return News::Join('likes', 'news.id', '=', 'likes.news_id')
            ->select('*', 'news.id')
            ->with('add_category')
            ->where('likes.user_id', Auth::id())
            ->orderBy('likes.id', 'desc')
            ->paginate(10);
    }
}
