<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrepareToChat
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $user;

    public function __construct($chat)
    {
        $this->chat = $chat;
        $this->user = auth()->guard('api')->user();
    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
