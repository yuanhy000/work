<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'openid',
    ];

    protected $hidden = [
        'password', 'remember_token', 'openid'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
}
