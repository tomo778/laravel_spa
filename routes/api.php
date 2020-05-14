<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/index', 'NewsController@index');
Route::get('/category', 'NewsController@list');
Route::get('/archive', 'NewsController@archive');

Route::post('/detail', 'NewsController@detail');
Route::post('/get/categorys', 'NewsController@getCategorys');
Route::post('/get/archives', 'NewsController@getArchives');

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
// ログインユーザー
Route::get(
    '/user',
    function () {
        return Auth::user();
    }
);

Route::post('/admin/user', 'Admin\UserController@list');
Route::post('/admin/user/detail/{id}', 'Admin\UserController@detail');
Route::post('/admin/user/update', 'Admin\UserController@update');

Route::post('/admin/news', 'Admin\NewsController@list');
Route::post('/admin/news/register', 'Admin\NewsController@register');
Route::post('/admin/news/detail/{id}', 'Admin\NewsController@detail');
Route::post('/admin/news/update', 'Admin\NewsController@update');
Route::post('/admin/news/selectbox', 'Admin\NewsController@selectbox');
Route::post('/admin/news/sarch', 'Admin\NewsController@sarch');

Route::post('/admin/category', 'Admin\CategoryController@list');
Route::post('/admin/category/register', 'Admin\CategoryController@register');
Route::post('/admin/category/detail/{id}', 'Admin\CategoryController@detail');
Route::post('/admin/category/update', 'Admin\CategoryController@update');
Route::post('/admin/category/selectbox', 'Admin\CategoryController@selectbox');
Route::post('/admin/category/sarch', 'Admin\CategoryController@sarch');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
