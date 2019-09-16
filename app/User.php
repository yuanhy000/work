<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

    public function zodiac()
    {
        return $this->hasOne(Chinese_zodiac::class, 'id', 'zodiac_id');
    }

    public function constellation()
    {
        return $this->hasOne(Constellation::class, 'id', 'constellation_id');
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

    public static function updateUser($user, $userInfo)
    {
        DB::beginTransaction();
        try {
            $user->name = $userInfo['user_name'];
            $user->email = $userInfo['user_email'];
            $user->phone = $userInfo['user_phone'];
            $user->avatar = $userInfo['user_avatar'];
            $user->sex = $userInfo['user_sex'];
            $user->signature = $userInfo['user_signature'];
            $user->birth = $userInfo['user_birth'];
            $user->blood_type = $userInfo['user_blood_type'];
            $user->address = $userInfo['user_address'];
            $user->hometown = $userInfo['user_hometown'];
            $user->school = $userInfo['user_school'];
            $user->age = $userInfo['user_age'];
            $user->zodiac_id = $userInfo['user_zodiac']['zodiac_id'];
            $user->constellation_id = $userInfo['user_constellation']['constellation_id'];
            $user->save();
            DB::commit();
            return $user;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
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
