<?php

namespace App\Http\Controllers;

use App\Events\AddFriend;
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
}
