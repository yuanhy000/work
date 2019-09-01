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

Route::get('/cache', function () {
    $user = \App\User::find(1);
    return new \App\Http\Resources\UserResource($user);
});

