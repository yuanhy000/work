<?php

namespace App\Http\Controllers;

use App\Chinese_zodiac;
use App\Constellation;
use App\Friend;
use App\Http\Resources\ConstellationResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ZodiacResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function AlibabaCloud\Client\json;

class UserController extends Controller
{
    public function getZodiac(Request $request)
    {
        $zodiac = Chinese_zodiac::all();
        return ZodiacResource::collection($zodiac);
    }

    public function getConstellation(Request $request)
    {
        $zodiac = Constellation::all();
        return ConstellationResource::collection($zodiac);
    }

    public function updateUser(Request $request)
    {
        $user = auth()->guard('api')->user();
        $userInfo = json_decode($request->getContent('userInfo'), true);

        $user = User::updateUser($user, $userInfo);
        if ($user) {
            return new UserResource($user);
        }
        return response()->json([
            'error' => '用户信息更新失败，请稍后再试'
        ], 408);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->guard('api')->user();
        $password = Hash::make($request->getContent('password'));
        $user->password = $password;
        $result = $user->save();
        if ($result) {
            return response()->json([
                'message' => '密码修改成功'
            ], 201);
        }
        return response()->json([
            'error' => '密码修改失败，请稍后再试'
        ], 408);
    }

    public function bindPhone(Request $request)
    {
        $user = auth()->guard('api')->user();
        $phone = json_decode($request->getContent('bindInfo'), true)['phone'];
        $code = json_decode($request->getContent('bindInfo'), true)['code'];

        if (Cache::get('bind.code.' . $phone) == $code) {
            $user->phone = $phone;
            $result = $user->save();
            if ($result) {
                return new UserResource($user);
            }
            return response()->json([
                'error' => '手机绑定失败，请稍后再试'
            ], 408);
        }
        return response()->json([
            'status' => false,
            'message' => '验证码不匹配，请重试'
        ], 404);
    }

    public function isFriend(Request $request)
    {
        $friend_id = (int)$request->getContent('user_id');
        $user_id = auth()->guard('api')->user()->id;

        $friend = Friend::where([['user_id', '=', $user_id], ['friend_id', '=', $friend_id]])->first();
        if ($friend_id == $user_id || $friend) {
            return response()->json([
                'isFriend' => true
            ], 200);
        }
        return response()->json([
            'isFriend' => false
        ], 200);
    }
}
