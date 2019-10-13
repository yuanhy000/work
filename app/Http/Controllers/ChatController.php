<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Friend;
use App\Http\Resources\ChatResource;
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
        ])->orWhere([
            ['from_user_id', '=', $friend_id],
            ['to_user_id', '=', $user_id]
        ])->first();

//        $chat->friend_id = $friend_id;
//        $chat->user_id = $user_id;

        return new ChatResource($chat);
//        return new FriendResource($friend_info);
    }
}
