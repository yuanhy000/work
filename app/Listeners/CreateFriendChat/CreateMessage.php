<?php

namespace App\Listeners\CreateFriendChat;

use App\Exceptions\BaseException;
use App\Message_chat;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Exception;

class CreateMessage
{

    public function handle($event)
    {
        $this->createMessageChat($event->chat_id, $event->chat_content, $event->accept_id);
    }

    public function createMessageChat($chat_id, $chat_content, $accept_id)
    {
        try {
            Message_chat::create([
                'chat_id' => $chat_id,
                'content' => $chat_content,
                'from_user_id' => auth()->guard('api')->user()->id,
                'to_user_id' => $accept_id,
                'status' => 0
            ]);
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '发送消息失败，请稍后再试'
            ], 408);
        }
    }
}
