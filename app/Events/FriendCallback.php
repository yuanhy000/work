<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FriendCallback implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $request_user;
    public $accept_user;
    public $message;

    public function __construct($request_id, $accept_id, $message)
    {
        $this->request_user = User::find($request_id);
        $this->accept_user = User::find($accept_id);
        $this->message = $message;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('Friend.callback.' . $this->request_user->id);
    }
}
