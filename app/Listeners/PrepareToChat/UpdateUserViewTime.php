<?php

namespace App\Listeners\PrepareToChat;

use App\Chat;
use App\Events\PrepareToChat;
use App\Exceptions\BaseException;
use Carbon\Carbon;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserViewTime
{
    public function __construct()
    {
        //
    }

    public function handle(PrepareToChat $event)
    {
        $this->UpdateUserLastViewTime($event->chat->id, $event->user->id);
    }

    public function UpdateUserLastViewTime($chat_id, $user_id)
    {
        try {
            $chat = Chat::find($chat_id);
            if ($chat->from_user_id == $user_id) {
                $chat->from_user_view_at = Carbon::now()->toDateTimeString();;
            } else if ($chat->to_user_id == $user_id) {
                $chat->to_user_view_at = Carbon::now()->toDateTimeString();;
            }
            $chat->save();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '更新用户最后聊天时间失败'
            ], 408);
        }
    }
}
