<?php

namespace App\Http\Controllers\Admin;

use App\News;
use App\CategoryRel;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminNews;

class NewsController extends Controller
{
    // public function __construct()
    // {
    //     // 認証が必要
    //     $this->middleware('auth')->except(['index', 'download', 'show']);
    // }
    // public function __construct()
    // {
    //     // 認証が必要
    //     $this->middleware('auth');
    // }
    public function list()
    {
        $news = News::with('add_category')->orderBy('id', 'desc')->paginate(20);
        return $news;
    }

    public function register(AdminNews $request)
    {
        $q = News::query();
        $q->fill($request->all())->save();
        CategoryRel::insertRel($request->category, $q->id);
        return $q->id;
    }

    public function update(AdminNews $request)
    {
        $q = News::findOrFail($request->id);
        $q->fill($request->all())->save();
        CategoryRel::deleteRel($request->id);
        CategoryRel::insertRel($request->category, $request->id);
        return response(200);
    }

    public function detail(Request $request)
    {
        $news = News::with('category_rel')->find($request->id)->toArray();
        //重要
        $news["category"] = array();
        foreach ($news['category_rel'] as $k => $v) {
            $news["category"][] = $v['category_id'];
        }
        return $news;
    }

    public function sarch(Request $request)
    {
        $q = News::with('add_category');
        if ($request->status) {
            $q = $q->where('status', $request->status);
        }
        if ($request->freeword) {
            $q = Common::fw_search($q, $request->freeword, ['title', 'text']);
        }
        $news = $q->orderBy('id', 'desc')->paginate(20);

        return $news;
    }

    public function selectbox(Request $request)
    {
        if ($request->mode == 1) {
            $this->on($request);
        }
        if ($request->mode == 2) {
            $this->off($request);
        }
        if ($request->mode == 9) {
            $this->delete($request);
        }
        return response(200);
    }

    private function on($request)
    {
        News::whereIn('id', $request->vals)->update(['status' => 1]);
    }

    private function off($request)
    {
        News::whereIn('id', $request->vals)->update(['status' => 2]);
    }

    private function delete($request)
    {
        News::destroy($request->vals);
    }
}
