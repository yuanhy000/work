<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\CreateFriendChat;
use App\Friend;
use App\Http\Resources\ChatResource;
use App\Http\Resources\FriendResource;
use App\Message_chat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getFriendChat(Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $friend_id = (int)$request->getContent();

        $chat = Chat::getChat($user_id, $friend_id);

        return new ChatResource($chat);
    }

    public function createFriendChat(Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $accept_id = json_decode($request->getContent(), true)['accept_id'];
        $chat_id = json_decode($request->getContent(), true)['chat_id'];
        $input_content = json_decode($request->getContent(), true)['inputContent'];

        $result = Message_chat::create([
            'chat_id' => $chat_id,
            'content' => $input_content,
            'from_user_id' => $user_id,
            'to_user_id' => $accept_id,
            'status' => 0
        ]);

        broadcast(new CreateFriendChat($input_content, $accept_id))->toOthers();
        return response()->json([
            'msg' => '发送成功',
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}
