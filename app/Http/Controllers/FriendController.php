<?php

namespace App\Http\Controllers;

use App\Events\AddFriend;
use App\Events\FriendCallback;
use App\Friend;
use App\Notification;
use App\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{

    public function addFriend(Request $request)
    {
        $request_user = auth()->guard('api')->user();
        $accept_id = (int)$request->getContent('user_id');

        broadcast(new AddFriend($request_user->id, $accept_id))->toOthers();
        return Notification::addFriendNotification($request_user, $accept_id);
    }


    public function addFriendCallback(Request $request)
    {
        $accept_user = auth()->guard('api')->user();

        $requestInfo = json_decode($request->getContent(), true);
        $request_user = $requestInfo['request_user'];
        $notification_id = (int)$requestInfo['notification_id'];
        $operation = (boolean)$requestInfo['operation'];

        //修改用户所操作的消息的状态
        Notification::operateNotification($accept_user->id, $notification_id, $operation);
        $message = Friend::friendCallback($accept_user, $request_user, $operation);
        broadcast(new FriendCallback($request_user['user_id'], $accept_user->id, $message))->toOthers();

        return Notification::friendCallbackNotification($accept_user->id, $request_user['user_id'],
            $message, $operation);
    }
}
