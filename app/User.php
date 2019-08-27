<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, Searchable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'openid',
    ];

    protected $hidden = [
        'password', 'remember_token', 'openid'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function searchableAs()
    {
        return 'users';
    }

    public function validateForPassportPasswordGrant($password)
    {
        if (!Hash::needsRehash($password)) {
            return hash_equals($password, $this->password);
        }
        return Hash::check($password, $this->password);
    }

    public static function checkUserByPhone($phone, $code)
    {
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => '手机号码不存在,请重试'
            ], 409);
        }

        if (Cache::get('login.code.' . $phone) == $code) {
            return $user;
        }

        return response()->json([
            'status' => false,
            'message' => '验证码不匹配，请重试'
        ], 404);
    }

    public static function generateUserID()
    {
        static $seed = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $str = $seed[intval(date('Y')) - 2018];
        for ($i = 0; $i < 8; $i++) {
            $rand = rand(0, count($seed) - 1);
            $temp = $seed[$rand];
            $str .= $temp;
            unset($seed[$rand]);
            $seed = array_values($seed);
        }
        return $str;
    }
}
