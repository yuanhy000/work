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

        $notification = Notification::where('to_user_id', '=', $user_id)->paginate(5);
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
        $requestInfo = json_decode($request->getContent(), true);

        $delete_id = $requestInfo['delete_notification']['notification_id'];
        $current_page = $requestInfo['current_page'];

        return Notification::deleteNotification($delete_id,$user_id,$current_page);
    }
}
