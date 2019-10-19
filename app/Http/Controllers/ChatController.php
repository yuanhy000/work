<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\CreateFriendChat;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
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

        $current_time = Carbon::now()->toDateTimeString();
        $chat_id = $chat->id;

        $last_time = Chat::getUserLastViewTime($chat_id, $user_id);
        try {
            $result = Message_chat::whereBetween('created_at', [$last_time, $current_time])
                ->where('to_user_id', '=', $user_id)->update(['status' => 1]);
            dd($result);
        } catch (\Exception $exception) {
            throw new $exception;
        }
        return new ChatResource($chat);
    }

    public function createFriendChat(Request $request)
    {
        auth()->guard('api')->user();
        $accept_id = json_decode($request->getContent(), true)['accept_id'];
        $chat_id = json_decode($request->getContent(), true)['chat_id'];
        $input_content = json_decode($request->getContent(), true)['inputContent'];

        event(new CreateFriendChat($input_content, $accept_id, $chat_id));

        return response()->json([
            'msg' => '消息发送成功',
            'created_at' => Carbon::now()->toDateTimeString()
        ], 201);
    }

    public function getChatList(Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;

        $chat = Chat::getChatList($user_id);

        return new ChatCollection($chat);
    }

    public function clearUnreadMessage(Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $current_time = Carbon::now()->toDateTimeString();
        $chat_id = $request->getContent('chat_id');

        $last_time = Chat::getUserLastViewTime($chat_id, $user_id);
        try {
            Message_chat::whereBetween('created_at', [$last_time, $current_time])
                ->where('to_user_id', '=', $user_id)->update(['status' => 1]);
        } catch (\Exception $exception) {
            throw new $exception;
        }

    }
}
