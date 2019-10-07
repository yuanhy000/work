<?php

namespace App\Listeners\UserLogout;

use App\Events\UserLogout;
use App\Exceptions\BaseException;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfflineUser
{

    public function __construct()
    {
        //
    }

    public function handle(UserLogout $event)
    {
        try {
            $event->user->status = 0;
            $event->user->save();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '更新用户状态失败'
            ], 408);
        }
    }
}
