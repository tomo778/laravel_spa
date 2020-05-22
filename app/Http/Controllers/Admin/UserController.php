<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminUserUpdate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function update(AdminUserUpdate $request): \Illuminate\Http\Response
    {
        $array = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        if (empty($request->password)) {
            unset($array['password']);
        }
        Admin::where('id', $request->id)
            ->update($array);

        return response(200);
    }

    public function list(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Admin::orderBy('id', 'desc')->paginate(10);
    }

    public function list2(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::orderBy('id', 'desc')->paginate(10);
    }

    public function detail(Request $request): \App\Admin
    {
        return Admin::find($request->id);
    }
}
