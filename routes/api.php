<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Events\UserLogin;

Route::middleware('auth:api')->get('/user', 'UserController@getUser');

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
Route::post('/users/friend_group', 'UserController@getFriendGroup');
Route::post('/users/offline', 'UserController@userOffline');

Route::get('/users/zodiac', 'UserController@getZodiac');
Route::get('/users/constellation', 'UserController@getConstellation');

Route::post('/friends/add', 'FriendController@addFriend');
Route::post('/friends/add/callback', 'FriendController@addFriendCallback');

Route::post('/notifications/unread', 'NotificationController@getUnread');
Route::post('/notifications/all', 'NotificationController@getNotification');
Route::post('/notifications/delete', 'NotificationController@deleteNotification');
Route::post('/notifications/read', 'NotificationController@readNotification');
Route::post('/notifications/is_apply', 'NotificationController@isApplyFriend');



