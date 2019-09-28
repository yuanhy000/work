<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public static function addFriendNotification($request_user, $accept_id)
    {
        $notification = Notification::create([
            'content' => '用户: ' . $request_user->name . ' 请求添加你为好友',
            'from_user_id' => $request_user->id,
            'to_user_id' => $accept_id,
            'status' => false,
            'type' => 'add-friend'
        ]);
        return (new self())->checkNotification($notification, '好友申请');
    }

    private function checkNotification($notification, $type)
    {
        if ($notification) {
            return response()->json([
                'msg' => $type . '发送成功！'
            ], 201);
        }
        return response()->json([
            'msg' => $type . '发送失败！'
        ], 201);
    }

    public static function getNotification($user_id)
    {

    }
}
