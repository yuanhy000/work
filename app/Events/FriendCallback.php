<?php

namespace App\Events;

use App\Notification;
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
    public $notification;
    public $operation;

    public function __construct($request_user, $accept_user, $notification_id, $operation)
    {
        $this->request_user = User::find($request_user['user_id']);
        $this->accept_user = $accept_user;
        $this->notification = Notification::find($notification_id);
        $this->operation = (boolean)$operation;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('Friend.callback.' . $this->request_user->id);
    }
}
