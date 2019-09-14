<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::post('/register/phone/code', 'SocialController@sendRegisterPhoneCode');
Route::post('/login/phone/code', 'SocialController@sendLoginPhoneCode');

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::post('/token/refresh', 'Auth\LoginController@refresh');

Route::post('/users/search', 'SearchController@user');

Route::get('/users/info/{id}', 'UserController@getInfo');

Route::get('/users/zodiac', 'UserController@getZodiac');
Route::get('/users/constellation', 'UserController@getConstellation');



