<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationCollection;
use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification()
    {
        $user_id = auth()->guard('api')->user()->id;

        $notification = Notification::where('to_user_id', '=', $user_id)->paginate(6);
        return new NotificationCollection($notification);
    }

    public function getUnread()
    {
        $user_id = auth()->guard('api')->user()->id;

        $notification = Notification::where([
            ['to_user_id', '=', $user_id],
            ['status', '=', 0]
        ])->get();
        return new NotificationCollection($notification);
    }

    public function deleteNotification(Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $delete_id = json_decode($request->getContent('delete_notification'), true)
        ['notification_id'];

        return Notification::deleteNotification($delete_id);
    }
}
