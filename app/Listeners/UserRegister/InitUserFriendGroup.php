<?php

namespace App\Listeners\UserRegister;

use App\Events\UserRegister;
use App\Exceptions\BaseException;
use App\FriendGroup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Exception;

class InitUserFriendGroup
{

    public function __construct()
    {
        //
    }

    public function handle(UserRegister $event)
    {
        try {
            FriendGroup::create([
                'user_id' => $event->user->id,
                'name' => '我的好友'
            ]);
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '好友分组初始化失败'
            ], 408);
        }
    }
}
