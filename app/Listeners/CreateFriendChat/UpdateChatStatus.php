<?php

namespace App\Listeners\CreateFriendChat;

use App\Chat;
use App\Exceptions\BaseException;
use App\Message_chat;
use Carbon\Carbon;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateChatStatus
{

    public function handle($event)
    {
        $this->updateChatStatus($event->chat_id);
    }

    public function updateChatStatus($chat_id)
    {
        try {
            $chat = Chat::find($chat_id)->first();
            $chat->updated_at = Carbon::now()->toDateTimeString();
            $chat->save();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '更新会话状态失败，请稍后再试'
            ], 408);
        }
    }
}
