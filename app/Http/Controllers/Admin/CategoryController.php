<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminCategory;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderByRaw('sort_num IS NULL ASC')
        ->orderBy('sort_num', 'ASC')
        ->get();
    }

    public function list(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Category::orderBy('id', 'DESC')
        ->paginate(10);
    }

    public function validation(AdminCategory $request): bool
    {
        return true;
    }

    public function register(AdminCategory $request): int
    {
        $q = Category::create();
        $q->fill($request->all())->save();
        return $q->id;
    }

    public function update(AdminCategory $request): \Illuminate\Http\Response
    {
        $q = Category::findOrFail($request->id);
        $q->fill($request->all())->save();
        return response(200);
    }

    public function sort(Request $request): \Illuminate\Http\Response
    {
        foreach ($request->toArray() as $k => $v) {
            $q = Category::find($v['id']);
            $q->sort_num = $v['sort_num'];
            $q->save();
        }
        return response(200);
    }

    public function detail(Request $request): \App\Category
    {
        return Category::find($request->id);
    }
}
