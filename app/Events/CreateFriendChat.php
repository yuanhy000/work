<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateFriendChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_content;
    public $accept_id;

    public function __construct($chat_input, $accept_id)
    {
        $this->chat_content = $chat_input;
        $this->accept_id = $accept_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Chat.accept.' . $this->accept_id);
//        return new Channel('Chat.accept.' . $this->accept_id);
    }

    public function broadcastWith()
    {
        return [
            'content' => $this->chat_content,
            'from_user' => new UserResource(auth()->guard('api')->user()),
            'created_at' => Carbon::now()->toDateTimeString()
        ];
    }
}
