<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Friend extends Model
{
    protected $guarded = [];


    public static function friendCallback($accept_user, $request_user, $operation)
    {
        if ($operation == true) {
            $result = Friend::createFriend($accept_user, $request_user);
            if (!$result) {
                throw new Exception('创建好友失败，请稍后再试', 408);
            }
            return '用户: ' . $accept_user->name . ' 同意了你的好友申请';
        } else {
            return '用户: ' . $accept_user->name . ' 拒绝了你的好友申请';
        }
    }

    public static function createFriend($accept_user, $request_user)
    {
        DB::beginTransaction();
        try {
            $friend = Friend::firstOrCreate([
                'user_id' => $accept_user->id,
                'friend_id' => $request_user['user_id']
            ], [
                'user_id' => $accept_user->id,
                'friend_id' => $request_user['user_id'],
                'friend_name' => $request_user['user_name']
            ]);

            $reverse_friend = Friend::firstOrCreate([
                'user_id' => $request_user['user_id'],
                'friend_id' => $accept_user->id
            ], [
                'user_id' => $request_user['user_id'],
                'friend_id' => $accept_user->id,
                'friend_name' => $accept_user->name
            ]);
            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
