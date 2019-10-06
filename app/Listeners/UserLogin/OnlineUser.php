<?php

namespace App\Listeners\UserLogin;

use App\Events\UserLogin;
use App\Exceptions\BaseException;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnlineUser
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

    public function handle(UserLogin $event)
    {
        try {
            $event->user->status = 1;
            $event->user->save();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '更新用户状态失败'
            ], 408);
        }
    }
}
