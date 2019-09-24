<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::post('/register/phone/code', 'SocialController@sendRegisterPhoneCode');
Route::post('/login/phone/code', 'SocialController@sendLoginPhoneCode');
Route::post('/bind/phone/code', 'SocialController@sendBindPhoneCode');
Route::post('/bind/phone', 'UserController@bindPhone');

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::post('/token/refresh', 'Auth\LoginController@refresh');

Route::post('/users/search', 'SearchController@user');

Route::get('/users/info/{id}', 'UserController@getInfo');
Route::post('/users/update', 'UserController@updateUser');
Route::post('/users/password', 'UserController@updatePassword');
Route::post('/users/is_friend', 'UserController@isFriend');

Route::get('/users/zodiac', 'UserController@getZodiac');
Route::get('/users/constellation', 'UserController@getConstellation');

Route::post('/friends/add', 'FriendController@addFriend');



