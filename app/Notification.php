<?php

namespace App;

use App\Http\Resources\NotificationCollection;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

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
