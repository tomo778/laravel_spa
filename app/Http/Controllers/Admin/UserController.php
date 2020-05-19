<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserUpdate;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Object_;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(UserUpdate $request): \Illuminate\Http\Response
    {
        $array = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        if (empty($request->password)) {
            unset($array['password']);
        }
        User::where('id', $request->id)
            ->update($array);

        return response(200);
    }

    public function list(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::orderBy('id', 'desc')->paginate(10);
    }

    public function detail(Request $request): Object
    {
        return User::find($request->id);
    }
}
