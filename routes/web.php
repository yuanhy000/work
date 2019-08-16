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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.master');
});


Route::get('/github/login', 'Auth\LoginController@githubLogin');
Route::get('/github/callback', 'Auth\LoginController@githubCallback');

Route::get('/cache', function () {
//    dd(\Illuminate\Support\Facades\Cache::get("login.code."."17784457936"));
    dd(\Illuminate\Support\Facades\Auth::id());
});

