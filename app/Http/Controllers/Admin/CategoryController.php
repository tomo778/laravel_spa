<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AdminCategory;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
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
        //0.1秒遅らせる
        usleep(100000);
        $news = Category::orderBy('id', 'desc')->paginate(10);
        return $news;
    }

    public function register(AdminCategory $request)
    {
        $q = new Category;
        $q->fill($request->all())->save();
        return $q->id;
    }

    public function update(AdminCategory $request)
    {
        $q = Category::findOrFail($request->id);
        $q->fill($request->all())->save();
        return response(200);
    }

    public function detail(Request $request)
    {
        $user = Category::find($request->id);
        return $user;
    }

    public function sarch(Request $request)
    {
        $q = DB::table('category');
        if ($request->status) {
            $q = $q->where('status', $request->status);
        }
        if ($request->freeword) {
            $q = Common::fw_search($q, $request->freeword, ['title', 'text']);
        }
        //usleep(100000);
        $news = $q->orderBy('id', 'desc')->paginate(10);

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
        Category::whereIn('id', $request->vals)->update(['status' => 1]);
    }

    private function off($request)
    {
        Category::whereIn('id', $request->vals)->update(['status' => 2]);
    }

    private function delete($request)
    {
        Category::destroy($request->vals);
    }
}
