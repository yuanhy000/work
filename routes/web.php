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
//    dd(base64_decode('eyJpdiI6IjE2Z0hFWnhIQlc0YmM5NW04NWxtZ2c9PSIsInZhbHVlIjoiUkVDYnFSZFMrclRORVM2TVZKczREU2ZGbm5KazN1RG5oOFZlYjBiOVZXTXVRRjZLXC8rOXZvYmpiakxYVVAwNFRITlwvK1FsZXZxRDBxN2NBeHhMcGUweDBadCtLN29SWVRSTEc3cUgxNzZmTjN1MXhwd0ZvN0tVUHl4U3BFaHdyRWlIN1VWbHArZzFBR2tTaWE5TlwvS1NZUndMeWZOdWxoVUNENWpLeHBnRTJVYis5dzBZMXVtOUtVTVowVThiNVU0V3lSZEg0RXk0OFJvUitaNzBGWGRUZ0Y3UFhVb28wT29senBYTHIzU3FqdHZKS2hWUnlic1BMSnFNMEhJb3pqa1l5MnNSdUw2amxTdTZiSUlhQkt3T3o2b3E2RVFMV0NuNEpyR3IwTEFsbDRNR2FFQjR4Zk05ZWw3T2x3TGF5NGJKeDVDdWE2K1d3cnpnRVFXcU9ZbHZoZ0NsQXM0T0RaQnRNXC9vNTFoY2FIXC9QelJwcHN5aFwvOTRHc2FjZW9TWFl3VFwvUm9ESGhXcEdISGJHVEZYK3RCdXVPeWg3SXhnbk93aXAzZVVBeEJiK1Z6R3o0a082WStuSG8reTM0ZkM2UjhGN09OVFl1UndCM09mOGhlQ3ZwYVdmOEZqXC9OakJXQnV3dCtkY3NhU0hIdTFDblJ3QzlHbWt6RUJqc3Y0MytHY3lUd3ZibmRZakN5TkNJUjh6dWo1Q09VOWp5bjNcL0VycWVZdnF6Y1wvTEhKS1dYZlZzRDFaUW1IN3lGUEU1N3AxV0FoVW9yU0laUkROWDZZR1p0dEhcL2tpcmRSRkdoUitjQWN0WWhza1RjV0QzbmhIU1ByUG9JQ0pqMjZacUxtMlRkcXZrM3AwSWIzRXloYVVkcVFKTFlZdUJOTlpOV2loQUFWU2dwRjQ3SGVWZStLNGZSOXRsbVl5cFMraFNncUFcL2diays4SE90MmFNaVV0c0NLankxRGdiQnhNZE9qeklQMmxXZ0JzSjczNkZzbWd6Z3pMMDhKV1wvc0x1MXJEMk84OGF3UUFKN3NJR2VqSFpGVFRKaDRnQW1NT1I2eHNIeHNxcXlNWW5aODQ4MXhVVGFrcTBNVUo4aG1IcDVSdG90TGt5SG1ySEZlc1VwNDFRejdERXBCY0JiZVk4S3ZCOStkUUQwRHJNQ0RXMThncEROS29vb25makRkQ1BtM1QxUzBEeHJQK3g0ZXJuVXRKUXYwYWxcL3FtdGJjRjZnaGMxaDg2a0N2Z2pXMStzY2hPRzZvWUhOWFU1YVBOYjJWU2VpeDIiLCJtYWMiOiI5NWRiOTA2MTlmMzFhMmJmM2FkMzA1MWViNTlkYjNjMTRkZjAxZjcyMDZkN2Y0NTUxMjhkMTE4OWFkMDVkYTRjIn0%3D'));
//    dd(uniqid('aaaaaa'));
//    $number = random_int(10000000, 99999999);
//    $yCode = array('1', '2', '3', '4', '5', '6', '7', '8');
//    $orderNo = $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m')))
//        . date('d') . substr(time(), -1) . substr(microtime(), 3, 2)
//        . sprintf('%02d', rand(0, 99));
//    return $orderNo;
    dd(\App\User::where('id','>',3)->delete());
});

