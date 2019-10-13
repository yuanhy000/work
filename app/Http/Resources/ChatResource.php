<?php

namespace App\Http\Resources;

use App\Friend;
use App\Message_chat;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public $friend;

//    public function __construct($request)
//    {
//
//    }

    public function toArray($request)
    {
        $this->friend = Friend::where([
            ['user_id', '=', auth()->guard('api')->user()->id],
            ['friend_id', '=', $this->to_user_id]
        ])->orWhere([
            ['user_id', '=', auth()->guard('api')->user()->id],
            ['friend_id', '=', $this->from_user_id]
        ])->first();

        return [
            'friend_name' => $this->friend->friend_name,
            'friend_info' => new UserResource(User::find($this->friend->friend_id)),
            'chat_info' => Message_chat::find($this->id)->orderBy('created_at', 'desc')
                ->paginate(10)
        ];
    }
}
