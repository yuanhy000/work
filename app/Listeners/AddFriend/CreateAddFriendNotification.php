<?php

namespace App\Listeners\AddFriend;

use App\Events\AddFriend;
use App\Exceptions\BaseException;
use App\Notification;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAddFriendNotification
{

    public function handle(AddFriend $event)
    {
        $this->addFriendNotification($event->request_user, $event->accept_user);
    }

    public function addFriendNotification($request_user, $accept_user)
    {
        try {
            Notification::create([
                'content' => '用户: ' . $request_user->name . ' 请求添加你为好友',
                'from_user_id' => $request_user->id,
                'to_user_id' => $accept_user->id,
                'status' => false,
                'type' => 'add-friend'
            ]);
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '创建通知失败，请稍后再试'
            ], 408);
        }
    }

}
