<?php

namespace App;

use App\Http\Resources\NotificationCollection;
use Exception;
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
        return (new self())->checkNotification($notification, '好友申请发送');
    }

    public static function friendCallbackNotification($request_id, $accept_id, $message, $operation)
    {
        $notification = Notification::create([
            'content' => $message,
            'from_user_id' => $request_id,
            'to_user_id' => $accept_id,
            'status' => false,
            'type' => 'friend-callback',
            'operation' => $operation
        ]);

        return (new self())->checkNotification($notification, '用户操作');
    }

    public static function operateNotification($user_id, $notification_id, $operation)
    {
        $notification = Notification::where([
            ['id', '=', $notification_id],
            ['to_user_id', '=', $user_id]
        ])->first();
        $notification->operation = $operation;
        $result = $notification->save();
        if (!$result) {
            throw new Exception('创建好友失败，请稍后再试', 408);
        }
    }

    private function checkNotification($notification, $type)
    {
        if ($notification) {
            return response()->json([
                'msg' => $type . '成功！'
            ], 201);
        }
        return response()->json([
            'msg' => $type . '失败！'
        ], 201);
    }

    public static function deleteNotification($delete_id, $user_id, $current_page)
    {
        $result = Notification::find($delete_id)->delete();
        if ($result) {
            $notification = Notification::where('to_user_id', '=', $user_id)
                ->paginate(5, '*', 'page', $current_page);
            return new NotificationCollection($notification);
        }
        return response()->json([
            'msg' => '消息删除失败'
        ], 404);
    }

    public static function readNotification($user_id, $read_id)
    {
        $notification = Notification::where([['id', '=', $read_id], ['to_user_id', '=', $user_id]])->first();
        $notification->status = true;
        $result = $notification->save();
        if ($result) {
            return response()->json([
                'msg' => '通知状态更新成功'
            ], 200);
        }
        return response()->json([
            'msg' => '通知状态更新失败'
        ], 408);
    }

    public static function NotificationIsExist($request_id, $accept_id, $type)
    {
        $notification = Notification::where([
            ['from_user_id', '=', $request_id],
            ['to_user_id', '=', $accept_id],
            ['type', '=', $type],
            ['operation', '=', null]
        ])->first();

        return $notification;
    }
}
