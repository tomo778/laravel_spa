<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserUpdate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function update(UserUpdate $request)
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

    public function list()
    {
        $users = User::orderBy('id', 'desc')->paginate();
        return $users;
    }

    public function detail(Request $request)
    {
        $user = User::find($request->id);
        return $user;
    }
}
