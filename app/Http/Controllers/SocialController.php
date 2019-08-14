<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Overtrue\EasySms\EasySms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class SocialController extends Controller
{
    public function sendRegisterPhoneCode(Request $request)
    {
        $phone = \request()->phone;
        $code = random_int(100000, 999999);

        Cache::put('register.code.' . $phone, $code, 600);
        return $this->sendPhoneCode($phone, $code);
    }

    public function register(Request $request)
    {
        $userInfo = $request->all();
        $result = Validator::make($userInfo, [
            'userName' => 'required|string|max:255|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:9',
            'phone' => 'required|string|size:11|unique:users',
            'code' => 'in:' . Cache::get('register.code.' . $userInfo['phone'])
        ])->validate();

        if (is_object($result)) {
            return response()->json('error!');
        }

        $user = User::create([
            'name' => $userInfo['userName'],
            'email' => $userInfo['email'],
            'password' => bcrypt($userInfo['password']),
            'phone' => $userInfo['phone'],
        ]);
        return response()->json('great!');
    }

    private function sendPhoneCode($phone, $code)
    {
        $easySms = new EasySms(config('sms'));

        $result = $easySms->send($phone, [
            'template' => 'SMS_171745738',
            'data' => [
                'code' => $code
            ],
        ]);
        dd($result);
        if ($result['aliyun']['status'] == 'success') {
            return response()->json([
                'status' => 'success'
            ], 200);
        }
        return response()->json([
            'status' => 'failed'
        ]);
    }
}
