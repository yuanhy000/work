<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public static function getChatList($user_id)
    {
        return Chat::where([
            ['from_user_id', '=', $user_id],
        ])->orWhere([
            ['to_user_id', '=', $user_id]
        ])->orderBy('updated_at', 'desc')->get();
    }

    public static function getChat($user_id, $friend_id)
    {
        $chat = Chat::where([
            ['from_user_id', '=', $user_id],
            ['to_user_id', '=', $friend_id]
        ])->orWhere([
            ['from_user_id', '=', $friend_id],
            ['to_user_id', '=', $user_id]
        ])->first();
        if (!$chat) {
            return (new self())->initChat($user_id, $friend_id);
        }
        return $chat;
    }

    public function initChat($user_id, $friend_id)
    {
        return Chat::create([
            'from_user_id' => $user_id,
            'to_user_id' => $friend_id
        ]);
    }
}
