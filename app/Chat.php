<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public static function getChat($user_id, $friend_id)
    {
        return Chat::where([
            ['from_user_id', '=', $user_id],
            ['to_user_id', '=', $friend_id]
        ])->orWhere([
            ['from_user_id', '=', $friend_id],
            ['to_user_id', '=', $user_id]
        ])->first();
    }
}
