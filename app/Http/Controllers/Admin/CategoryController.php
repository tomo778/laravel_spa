<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminCategory;


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
    public function index()
    {
        $category = Category::orderByRaw('sort_num IS NULL ASC')
        ->orderBy('sort_num', 'ASC')
        ->get();
        return $category;
    }

    public function list()
    {
        $category = Category::orderBy('id', 'DESC')
        ->paginate(10);
        return $category;
    }

    public function register(AdminCategory $request)
    {
        $q = Category::query();
        $q->fill($request->all())->save();
        return $q->id;
    }

    public function update(AdminCategory $request)
    {
        $q = Category::findOrFail($request->id);
        $q->fill($request->all())->save();
        return response(200);
    }

    public function sort(Request $request)
    {
        foreach ($request->toArray() as $k => $v) {
            $q = Category::find($v['id']);
            $q->sort_num = $v['sort_num'];
            $q->save();
        }
        return response(200);
    }

    public function detail(Request $request)
    {
        $user = Category::find($request->id);
        return $user;
    }
}