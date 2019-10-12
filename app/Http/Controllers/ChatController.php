<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Friend;
use App\Http\Resources\FriendResource;
use App\Message_chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getFriendChat(Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $friend_id = (int)$request->getContent();

        $chat = Chat::where([
            ['from_user_id', '=', $user_id],
            ['to_user_id', '=', $friend_id]
        ])->first();
//        if(!$chat){
//
//        }
//            ->orderBy('created_at', 'desc')->paginate(5);

        return $chat;
//        return new FriendResource($friend_info);
    }
}
