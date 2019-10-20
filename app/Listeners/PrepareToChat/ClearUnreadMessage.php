<?php

namespace App\Listeners\PrepareToChat;

use App\Chat;
use App\Events\PrepareToChat;
use App\Exceptions\BaseException;
use App\Message_chat;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Exception;

class ClearUnreadMessage
{

    public function __construct()
    {
        //
    }


    public function handle(PrepareToChat $event)
    {
        $this->clearUnreadMessage($event->chat->id, $event->user->id);
    }

    public function clearUnreadMessage($chat_id, $user_id)
    {
        $current_time = Carbon::now()->toDateTimeString();

        $last_time = Chat::getUserLastViewTime($chat_id, $user_id);
        try {
            Message_chat::whereBetween('created_at', [$last_time, $current_time])
                ->where('to_user_id', '=', $user_id)->update(['status' => 1]);
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '清除未读消息失败'
            ], 408);
        }
    }
}
