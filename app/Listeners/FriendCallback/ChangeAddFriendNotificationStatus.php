<?php

namespace App\Listeners\FriendCallback;

use App\Events\FriendCallback;
use App\Exceptions\BaseException;
use App\Notification;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeAddFriendNotificationStatus
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
        $this->operateNotification($event->notification, $event->operation);
    }

    public function operateNotification($notification, $operation)
    {
        try {
            $notification->operation = $operation;
            $notification->save();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '修改通知状态失败，请稍后再试'
            ], 408);
        }
    }
}
