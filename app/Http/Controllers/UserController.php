<?php

namespace App\Http\Controllers;

use App\Chinese_zodiac;
use App\Constellation;
use App\Http\Resources\ConstellationResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ZodiacResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getInfo(Request $request)
    {

    }

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
        ], 404);
    }
}
