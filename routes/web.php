<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('layouts.master');
});


Route::get('/github/login', 'Auth\LoginController@githubLogin');
Route::get('/github/callback', 'Auth\LoginController@githubCallback');

//Route::post('/broadcast/auth', 'Auth\LoginController@broadcastAuth');

Route::get('/new', function () {
//    event(new \App\Events\UserRegister(\App\User::find(1)));
//    throw new \App\Exceptions\BaseException([
//        'msg' => 'error !!!'
//    ], 404);
//    event(new \App\Events\AddFriend(1, 2));
    dd(\App\Friend::where('id',9)->with('user')->first());
});

