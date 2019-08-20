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
//    dd(\Illuminate\Support\Facades\Cache::get("login.code."."17784457936"));
//    dd(\Illuminate\Support\Facades\Auth::id());
        dd(hash_equals('$2y$10$0yNs45vZx8U63S5RTI7AKuV4DujaugQJUA9L6w.46BrkVo7jh9Xli','$2y$10$0yNs45vZx8U63S5RTI7AKuV4DujaugQJUA9L6w.46BrkVo7jh9Xli'));
});

