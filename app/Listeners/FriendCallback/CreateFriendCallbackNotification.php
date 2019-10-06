<?php

namespace App\Listeners\FriendCallback;

use App\Events\FriendCallback;
use App\Exceptions\BaseException;
use App\Notification;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFriendCallbackNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(FriendCallback $event)
    {
        $this->friendCallbackNotification($event->accept_user, $event->request_user, $event->operation);
    }

    public function friendCallbackNotification($request_user, $accept_user, $operation)
    {
        if ($operation) {
            $message = '用户: ' . $request_user->name . ' 同意了你的好友申请';
        } else {
            $message = '用户: ' . $request_user->name . ' 拒绝了你的好友申请';
        }
        try {
            Notification::create([
                'content' => $message,
                'from_user_id' => $request_user->id,
                'to_user_id' => $accept_user->id,
                'status' => false,
                'type' => 'friend-callback',
                'operation' => $operation
            ]);
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '创建通知失败，请稍后再试'
            ], 408);
        }
    }
}
