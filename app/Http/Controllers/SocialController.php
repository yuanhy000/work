<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class SocialController extends Controller
{
    public function sendRegisterPhoneCode(Request $request)
    {
        $phone = \request()->phone;
        $code = random_int(100000, 999999);

        Cache::put('register.code.' . $phone, $code, 600);
        return $this->sendPhoneCode($phone, $code);
    }

    public function sendLoginPhoneCode(Request $request)
    {
        $phone = \request()->phone;
        $code = random_int(100000, 999999);

        Cache::put('login.code.' . $phone, $code, 600);
        return $this->sendPhoneCode($phone, $code);
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
