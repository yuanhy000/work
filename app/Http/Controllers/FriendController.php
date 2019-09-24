<?php

namespace App\Http\Controllers;

use App\Events\AddFriend;
use App\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function addFriend(Request $request)
    {
        $friend_id = (int)$request->getContent('user_id');
        $user_id = auth()->guard('api')->user()->id;
//        dd($friend_id, $user_id);
        broadcast(new AddFriend($user_id, $friend_id));
    }
}
