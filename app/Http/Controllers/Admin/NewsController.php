<?php

namespace App\Http\Controllers\Admin;

use App\News;
use App\CategoryRel;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminNews;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return News::with('add_category')->orderBy('id', 'desc')->paginate(20);
    }

    public function validation(AdminNews $request): bool
    {
        return true;
    }

    public function register(AdminNews $request): int
    {
        $q = News::create();
        $q->fill($request->all())->save();
        CategoryRel::insertRel($request->category, $q->id);
        return $q->id;
    }

    public function update(AdminNews $request): \Illuminate\Http\Response
    {
        $q = News::findOrFail($request->id);
        $q->fill($request->all())->save();
        CategoryRel::deleteRel($request->id);
        CategoryRel::insertRel($request->category, $request->id);
        return response(200);
    }

    public function detail(Request $request): array
    {
        $news = array();
        $news = News::with('category_rel')->find($request->id)->toArray();
        foreach ($news['category_rel'] as $k => $v) {
            $news["category"][] = $v['category_id'];
        }
        return $news;
    }

    public function sarch(Request $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $q = News::with('add_category');
        if ($request->status) {
            $q = $q->where('status', $request->status);
        }
        if ($request->freeword) {
            $q = Common::fw_search($q, $request->freeword, ['title', 'text']);
        }
        return $q->orderBy('id', 'desc')->paginate(20);
    }

    public function selectbox(Request $request): \Illuminate\Http\Response
    {
        if ($request->mode == config('const.STATUS_ON')) {
            $this->on($request);
        }
        if ($request->mode == config('const.STATUS_OFF')) {
            $this->off($request);
        }
        if ($request->mode == config('const.STATUS_DELETE')) {
            $this->delete($request);
        }
        return response(200);
    }

    private function on(Request $request): void
    {
        News::whereIn('id', $request->vals)->update(['status' => config('const.STATUS_ON')]);
    }

    private function off(Request $request): void
    {
        News::whereIn('id', $request->vals)->update(['status' => config('const.STATUS_OFF')]);
    }

    private function delete(Request $request): void
    {
        News::destroy($request->vals);
    }
}
