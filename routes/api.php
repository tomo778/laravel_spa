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

/*
|--------------------------------------------------------------------------
| front(公開側)
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get(
    '/user',
    function () {
        return Auth::user();
    }
);

//NewsController
Route::get('/index', 'NewsController@index')->name('index');
Route::get('/category', 'NewsController@list');
Route::get('/archive', 'NewsController@archive');
Route::post('/detail', 'NewsController@detail');
Route::post('/get/categorys', 'NewsController@getCategorys');
Route::post('/get/archives', 'NewsController@getArchives');
Route::post(
    '/get/config',
    function () {
        return config('const');
    }
);

//MypageController
Route::get('/likes', 'MypageController@likes');
Route::put('/news/like/{id}', 'MypageController@like');
Route::delete('/news/like/{id}', 'MypageController@unlike');

/*
|--------------------------------------------------------------------------
| admin(管理側)
|--------------------------------------------------------------------------
*/

// Admin\Auth
Route::post('/admin/register', 'Admin\Auth\RegisterController@register');
Route::post('/admin/login', 'Admin\Auth\LoginController@login');
Route::post('/admin/logout', 'Admin\Auth\LoginController@logout');
Route::get(
    '/admin/user',
    function () {
        return Auth::guard('admin')->user();
    }
);

//Admin\UserController
Route::post('/admin/user/list2', 'Admin\UserController@list2');
Route::post('/admin/user/list', 'Admin\UserController@list');
Route::post('/admin/user/detail/{id}', 'Admin\UserController@detail');
Route::post('/admin/user/update', 'Admin\UserController@update');

//Admin\NewsController
Route::post('/admin/news', 'Admin\NewsController@list');
Route::post('/admin/news/register', 'Admin\NewsController@register');
Route::post('/admin/news/validation', 'Admin\NewsController@validation');
Route::post('/admin/news/detail/{id}', 'Admin\NewsController@detail');
Route::post('/admin/news/delete/{id}', 'Admin\NewsController@delete');
Route::post('/admin/news/update', 'Admin\NewsController@update');
Route::post('/admin/news/selectbox', 'Admin\NewsController@selectbox');
Route::post('/admin/news/sarch', 'Admin\NewsController@sarch');

//Admin\CategoryController
Route::post('/admin/category', 'Admin\CategoryController@index');
Route::post('/admin/category/list', 'Admin\CategoryController@list');
Route::post('/admin/category/register', 'Admin\CategoryController@register');
Route::post('/admin/category/validation', 'Admin\CategoryController@validation');
Route::post('/admin/category/detail/{id}', 'Admin\CategoryController@detail');
Route::post('/admin/category/update', 'Admin\CategoryController@update');
Route::post('/admin/category/sort', 'Admin\CategoryController@sort');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
